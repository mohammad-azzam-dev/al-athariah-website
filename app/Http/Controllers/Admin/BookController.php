<?php

namespace App\Http\Controllers\Admin;

use App\Data\LibraryData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookRequest;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $books = Book::query()
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('title', 'like', "%{$q}%")->orWhere('author', 'like', "%{$q}%")->orWhere('field', 'like', "%{$q}%")))
            ->when($request->filled('programme'), fn ($query) => $query->where('programme', $request->query('programme')))
            ->when($request->query('file') === 'with', fn ($query) => $query->whereNotNull('file_path'))
            ->when($request->query('file') === 'without', fn ($query) => $query->whereNull('file_path'))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.books.index', ['books' => $books] + $this->lookups());
    }

    public function create(): View
    {
        $next = (int) Book::max('sort_order') + 10;

        return view('admin.books.form', ['book' => new Book(['is_published' => true, 'kind' => 'book', 'sort_order' => $next])] + $this->lookups());
    }

    public function store(BookRequest $request): RedirectResponse
    {
        $book = new Book($this->attributes($request));
        $book->sort_order ??= (int) Book::max('sort_order') + 10;
        if ($request->hasFile('file')) {
            $this->storeFile($book, $request->file('file'));
        }
        $book->save();

        return redirect()->route('admin.books.index')->with('status', 'تمت إضافة «'.$book->title.'» إلى المكتبة.');
    }

    public function edit(Book $book): View
    {
        return view('admin.books.form', ['book' => $book] + $this->lookups());
    }

    public function update(BookRequest $request, Book $book): RedirectResponse
    {
        $book->fill($this->attributes($request));
        if ($request->hasFile('file')) {
            $book->deleteFile();                       // replace: remove the old copy first
            $this->storeFile($book, $request->file('file'));
        } elseif ($request->boolean('remove_file')) {
            $book->deleteFile();
        }
        $book->save();

        return redirect()->route('admin.books.index')->with('status', 'تم حفظ تعديلات «'.$book->title.'».');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $title = $book->title;
        $book->deleteFile();
        $book->delete();

        return redirect()->route('admin.books.index')->with('status', 'تم حذف «'.$title.'» وملفه.');
    }

    private function attributes(BookRequest $request): array
    {
        return collect($request->validated())->except(['file', 'remove_file'])->all();
    }

    private function storeFile(Book $book, UploadedFile $file): void
    {
        $cfg = config('athariyah.books');
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $path = $file->storeAs(trim($cfg['directory'], '/'), Str::uuid().'.'.$ext, ['disk' => $cfg['disk']]);

        $book->forceFill([
            'file_disk' => $cfg['disk'],
            'file_path' => $path,
            'file_name' => Str::limit($file->getClientOriginalName(), 250, ''),
            'file_mime' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    private function lookups(): array
    {
        $cat = LibraryData::catalogue();

        return [
            'groups' => collect($cat['groups'])->pluck('label', 'id')->all(),
            'programmes' => collect($cat['programmes'])->pluck('pageLabel', 'id')->all(),
            'kinds' => Book::KINDS,
        ];
    }
}
