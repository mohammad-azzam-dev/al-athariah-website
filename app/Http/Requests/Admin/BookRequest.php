<?php

namespace App\Http\Requests\Admin;

use App\Data\LibraryData;
use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        // Topics come from a textarea: one per line.
        $topics = collect(preg_split('/\r\n|\r|\n/', (string) $this->input('topics_text', '')))
            ->map(fn ($t) => trim($t))->filter()->values()->all();

        $this->merge([
            'topics' => $topics ?: null,
            'is_published' => $this->boolean('is_published'),
            'remove_file' => $this->boolean('remove_file'),
            'section' => $this->input('programme') === 'maqra' ? ($this->input('section') ?: null) : null,
        ]);
    }

    public function rules(): array
    {
        $books = config('athariyah.books');

        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'field' => ['required', 'string', 'max:120'],
            'group' => ['required', Rule::in(LibraryData::groupIds())],
            'programme' => ['required', Rule::in(LibraryData::programmeIds())],
            'section' => ['nullable', Rule::in(['matun'])],
            'level' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:120'],
            'note' => ['nullable', 'string', 'max:500'],
            'kind' => ['required', Rule::in(array_keys(Book::KINDS))],
            'topics' => ['nullable', 'array', 'max:100'],
            'topics.*' => ['string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'is_published' => ['boolean'],
            'remove_file' => ['boolean'],
            'file' => ['nullable', 'file', 'mimes:'.implode(',', $books['mimes']), 'max:'.$books['max_kb']],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'العنوان', 'author' => 'المؤلف', 'field' => 'المادة', 'group' => 'المجال',
            'programme' => 'البرنامج', 'section' => 'القسم', 'level' => 'المستوى', 'method' => 'طريقة الدراسة',
            'note' => 'ملاحظة', 'kind' => 'النوع', 'sort_order' => 'الترتيب', 'file' => 'الملف',
        ];
    }
}
