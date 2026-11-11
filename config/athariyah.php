<?php

return [
    'name' => 'مجمع دار العلوم الأثرية',
    'short_name' => 'دار العلوم الأثرية',
    'tagline' => 'قسم التعليم عن بُعد',

    // Digital copies of library books (admin uploads). Cloudflare R2 by default.
    'books' => [
        'disk' => env('BOOKS_DISK', 'r2'),
        'directory' => env('BOOKS_DIRECTORY', 'library'),
        'max_kb' => (int) env('BOOKS_MAX_KB', 102400),          // 100 MB
        'link_minutes' => (int) env('BOOKS_LINK_MINUTES', 30),  // signed download link lifetime
        'mimes' => ['pdf', 'epub', 'doc', 'docx'],
    ],

    // key => [route name, label]
    'nav' => [
        'home' => ['home', 'الرئيسية'],
        'college' => ['college', 'الكلية'],
        'school' => ['school', 'المدرسة'],
        'maqra' => ['maqra', 'المقرأة'],
        'library' => ['library', 'المكتبة'],
        'path' => ['path', 'مسار الطالب'],
    ],
];
