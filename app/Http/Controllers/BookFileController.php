<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/** Public access to a book's digital copy: a short-lived signed R2 link, or a stream for local disks. */
class BookFileController extends Controller
{
    public function __invoke(Book $book): Response
    {
        abort_unless($book->is_published && $book->hasFile(), 404);

        $disk = Storage::disk($book->file_disk);
        $name = $book->file_name ?: ($book->title.'.'.pathinfo($book->file_path, PATHINFO_EXTENSION));
        $disposition = "inline; filename*=UTF-8''".rawurlencode($name);

        if ($disk->providesTemporaryUrls()) {
            $url = $disk->temporaryUrl($book->file_path, now()->addMinutes(config('athariyah.books.link_minutes')), [
                'ResponseContentDisposition' => $disposition,
            ]);

            return redirect()->away($url);
        }

        abort_unless($disk->exists($book->file_path), 404);

        return $disk->response($book->file_path, $name, ['Content-Disposition' => $disposition]);
    }
}
