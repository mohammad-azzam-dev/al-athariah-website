<!DOCTYPE html>
<html lang="ar" dir="rtl" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('athariyah.name')) | {{ config('athariyah.name') }}</title>
    <meta name="description" content="@yield('description', 'مجمع دار العلوم الأثرية — التعليم الشرعي الرقمي التفاعلي: مدرسة وكلية ومكتبة ومقرأة في مكان واحد.')">
    <meta name="theme-color" content="#1E3A2C">
    <meta property="og:title" content="@yield('title', config('athariyah.name'))">
    <meta property="og:locale" content="ar_AR">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=IBM+Plex+Sans+Arabic:wght@400;500;600&family=Reem+Kufi:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    @stack('styles')
    <script>document.documentElement.classList.replace('no-js','js')</script>
</head>
<body data-page="{{ $page ?? Route::currentRouteName() }}">
    <div class="scroll-progress" aria-hidden="true"></div>
    <div class="page-intro" aria-hidden="true">
        <svg viewBox="0 0 120 120" class="page-intro__star">
            <g fill="none" stroke="#C9A24B" stroke-width="2">
                <rect x="25" y="25" width="70" height="70" pathLength="1"/>
                <rect x="25" y="25" width="70" height="70" transform="rotate(45 60 60)" pathLength="1"/>
                <circle cx="60" cy="60" r="20" pathLength="1"/>
            </g>
        </svg>
    </div>

    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
