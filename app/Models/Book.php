<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    public const KINDS = [
        'book' => 'كتاب',
        'matn' => 'متن',
        'course' => 'مقرر / حفظ',
        'topics' => 'مفردات مقرر',
    ];

    protected $fillable = [
        'title', 'author', 'field', 'group', 'programme', 'section', 'level', 'method', 'note',
        'kind', 'topics', 'sort_order', 'is_published',
        'file_disk', 'file_path', 'file_name', 'file_mime', 'file_size',
    ];

    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
            'file_size' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function hasFile(): bool
    {
        return filled($this->file_path) && filled($this->file_disk);
    }

    /** Remove the stored digital copy (if any) and clear its columns. */
    public function deleteFile(): void
    {
        if ($this->hasFile()) {
            Storage::disk($this->file_disk)->delete($this->file_path);
        }
        $this->forceFill(['file_disk' => null, 'file_path' => null, 'file_name' => null, 'file_mime' => null, 'file_size' => null]);
    }

    public function humanFileSize(): ?string
    {
        if (! $this->file_size) {
            return null;
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = (float) $this->file_size;
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, $i ? 1 : 0).' '.$units[$i];
    }

    /** Shape used by the public library page and its JavaScript. */
    public function toLibraryRecord(): array
    {
        return [
            'id' => 'b'.$this->id,
            'title' => $this->title,
            'author' => $this->author,
            'field' => $this->field,
            'group' => $this->group,
            'prog' => $this->programme,
            'hash' => $this->section,
            'level' => $this->level,
            'method' => $this->method,
            'note' => $this->note ?? '',
            'kind' => $this->kind,
            'topics' => $this->topics,
            'file' => $this->hasFile() ? route('library.book.file', $this) : null,
            'fileSize' => $this->humanFileSize(),
        ];
    }
}
