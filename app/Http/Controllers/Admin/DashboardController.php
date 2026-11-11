<?php

namespace App\Http\Controllers\Admin;

use App\Data\LibraryData;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $byProgramme = Book::query()->selectRaw('programme, count(*) as total, sum(case when file_path is not null then 1 else 0 end) as with_files')
            ->groupBy('programme')->get()->keyBy('programme');

        return view('admin.dashboard', [
            'stats' => [
                'total' => Book::count(),
                'published' => Book::published()->count(),
                'with_files' => Book::whereNotNull('file_path')->count(),
                'storage' => (int) Book::sum('file_size'),
            ],
            'byProgramme' => $byProgramme,
            'programmes' => collect(LibraryData::catalogue()['programmes'])->pluck('pageLabel', 'id')->all(),
            'latest' => Book::query()->latest('updated_at')->limit(8)->get(),
            'disk' => config('athariyah.books.disk'),
            'diskReady' => $this->diskReady(),
        ]);
    }

    /** Whether the configured storage for digital copies has its credentials. */
    private function diskReady(): bool
    {
        $disk = config('athariyah.books.disk');
        $cfg = config("filesystems.disks.{$disk}");
        if (! $cfg) {
            return false;
        }
        if (($cfg['driver'] ?? null) !== 's3') {
            return true;
        }

        return filled($cfg['key'] ?? null) && filled($cfg['secret'] ?? null) && filled($cfg['bucket'] ?? null) && filled($cfg['endpoint'] ?? null);
    }
}
