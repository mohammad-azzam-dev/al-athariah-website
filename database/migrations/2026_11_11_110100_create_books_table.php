<?php

use App\Data\LibraryData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('field', 120);                 // المادة / الفن as printed in the guide
            $table->string('group', 40);                  // one of LibraryData::catalogue()['groups']
            $table->string('programme', 20);              // school | college | maqra
            $table->string('section', 20)->nullable();    // e.g. 'matun' inside المقرأة
            $table->string('level');
            $table->string('method', 120);
            $table->string('note', 500)->nullable();
            $table->string('kind', 20)->default('book');  // book | course | matn | topics
            $table->json('topics')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            // Digital copy (stored on Cloudflare R2 by default)
            $table->string('file_disk', 40)->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_mime', 120)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'sort_order']);
            $table->index('programme');
        });

        // Start from the curriculum catalogue (الدليل الشامل ١٤٤٨هـ), so the public library
        // never shows a partial list once admins begin adding books.
        $now = now();
        $rows = [];
        foreach (LibraryData::seedRecords() as $i => $r) {
            $rows[] = [
                'title' => $r['title'],
                'author' => $r['author'] ?? null,
                'field' => $r['field'],
                'group' => $r['group'],
                'programme' => $r['prog'],
                'section' => $r['hash'] ?? null,
                'level' => $r['level'],
                'method' => $r['method'],
                'note' => ($r['note'] ?? '') !== '' ? $r['note'] : null,
                'kind' => $r['kind'] ?? 'book',
                'topics' => isset($r['topics']) ? json_encode($r['topics'], JSON_UNESCAPED_UNICODE) : null,
                'sort_order' => ($i + 1) * 10,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('books')->insert($chunk);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
