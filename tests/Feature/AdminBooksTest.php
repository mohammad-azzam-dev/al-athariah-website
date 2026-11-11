<?php

namespace Tests\Feature;

use App\Data\LibraryData;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBooksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['athariyah.books.disk' => 'r2']);
        Storage::fake('r2');
    }

    private function admin(): User
    {
        $user = User::factory()->create(['email' => 'admin@example.test']);
        $user->forceFill(['is_admin' => true])->save();

        return $user;
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'كتاب الاختبار',
            'author' => 'مؤلف الاختبار',
            'field' => 'التوحيد',
            'group' => 'aqeeda',
            'programme' => 'school',
            'level' => 'برنامج «درجات»',
            'method' => 'تفاعلي',
            'kind' => 'book',
            'is_published' => '1',
            'sort_order' => 5,
        ], $overrides);
    }

    public function test_the_migration_seeds_the_full_catalogue(): void
    {
        $this->assertSame(86, Book::count());
        $this->assertSame(86, count(LibraryData::get()['records']));
    }

    public function test_guests_are_redirected_to_the_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/books')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('تسجيل الدخول');
    }

    public function test_non_admins_cannot_sign_in_or_open_the_dashboard(): void
    {
        $user = User::factory()->create(['email' => 'user@example.test']);

        $this->post('/admin/login', ['email' => 'user@example.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admins_sign_in_and_see_the_dashboard(): void
    {
        $this->admin();

        $this->post('/admin/login', ['email' => 'admin@example.test', 'password' => 'password'])
            ->assertRedirect('/admin');
        $this->assertAuthenticated();

        $this->get('/admin')->assertOk()->assertSee('كتاب في المكتبة');
        $this->get('/admin/books')->assertOk()->assertSee('الكتب');
        $this->get('/admin/books/create')->assertOk();
        $this->get('/admin/profile')->assertOk();
    }

    public function test_an_admin_adds_a_book_with_a_file(): void
    {
        $this->actingAs($this->admin());
        $file = UploadedFile::fake()->create('test.pdf', 200, 'application/pdf');

        $this->post('/admin/books', $this->payload(['file' => $file]))
            ->assertRedirect('/admin/books')
            ->assertSessionHasNoErrors();

        $book = Book::where('title', 'كتاب الاختبار')->firstOrFail();
        $this->assertTrue($book->hasFile());
        $this->assertSame('r2', $book->file_disk);
        $this->assertSame('test.pdf', $book->file_name);
        Storage::disk('r2')->assertExists($book->file_path);

        $record = collect(LibraryData::get()['records'])->firstWhere('id', 'b'.$book->id);
        $this->assertSame(route('library.book.file', $book), $record['file']);

        $this->get('/library')->assertOk()->assertSee('كتاب الاختبار')->assertSee('نسخة رقمية');
    }

    public function test_validation_rejects_bad_input(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/books', $this->payload([
            'title' => '',
            'programme' => 'nope',
            'file' => UploadedFile::fake()->create('x.exe', 10),
        ]))->assertSessionHasErrors(['title', 'programme', 'file']);
    }

    public function test_an_admin_replaces_removes_and_deletes_files(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/books', $this->payload(['file' => UploadedFile::fake()->create('a.pdf', 50, 'application/pdf')]));
        $book = Book::where('title', 'كتاب الاختبار')->firstOrFail();
        $first = $book->file_path;

        $this->put("/admin/books/{$book->id}", $this->payload(['title' => 'عنوان جديد', 'file' => UploadedFile::fake()->create('b.pdf', 60, 'application/pdf')]))
            ->assertRedirect('/admin/books');
        $book->refresh();
        $this->assertSame('عنوان جديد', $book->title);
        Storage::disk('r2')->assertMissing($first);
        Storage::disk('r2')->assertExists($book->file_path);

        $second = $book->file_path;
        $this->put("/admin/books/{$book->id}", $this->payload(['remove_file' => '1']));
        $book->refresh();
        $this->assertFalse($book->hasFile());
        Storage::disk('r2')->assertMissing($second);

        $this->put("/admin/books/{$book->id}", $this->payload(['file' => UploadedFile::fake()->create('c.pdf', 10, 'application/pdf')]));
        $third = $book->refresh()->file_path;
        $this->delete("/admin/books/{$book->id}")->assertRedirect('/admin/books');
        $this->assertModelMissing($book);
        Storage::disk('r2')->assertMissing($third);
    }

    public function test_the_public_file_link_only_serves_published_books(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/books', $this->payload(['file' => UploadedFile::fake()->create('a.pdf', 20, 'application/pdf')]));
        $book = Book::where('title', 'كتاب الاختبار')->firstOrFail();
        auth()->logout();

        $response = $this->get(route('library.book.file', $book));
        $this->assertContains($response->getStatusCode(), [200, 302]);

        $book->update(['is_published' => false]);
        $this->get(route('library.book.file', $book))->assertNotFound();

        $plain = Book::whereNull('file_path')->first();
        $this->get(route('library.book.file', $plain))->assertNotFound();
    }

    public function test_hidden_books_are_not_listed_publicly(): void
    {
        Book::query()->update(['is_published' => false]);
        Book::first()->update(['is_published' => true]);

        $this->assertCount(1, LibraryData::get()['records']);
    }
}
