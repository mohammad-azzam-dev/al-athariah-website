<!DOCTYPE html>
<html lang="ar" dir="rtl" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('athariyah.name')) | {{ config('athariyah.name') }}</title>
    <meta name="description" content="@yield('description', 'مجمع دار العلوم الأثرية — التعليم الشرعي الرقمي التفاعلي: مدرسة وكلية ومكتبة ومقرأة في مكان واحد.')">
    <meta name="theme-color" content="#1E3A2C">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <meta property="og:image" content="{{ asset('images/brand/logo.png') }}">
    <meta property="og:title" content="@yield('title', config('athariyah.name'))">
    <meta property="og:locale" content="ar_AR">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=IBM+Plex+Sans+Arabic:wght@400;500;600&family=Reem+Kufi:wght@500;600;700&display=swap" rel="stylesheet">
    <script>
        // Apply the saved theme (or the system one) before first paint, so there is no flash.
        (function () {
            var t = null;
            try { t = localStorage.getItem('athariyah-theme'); } catch (e) {}
            if (t !== 'dark' && t !== 'light') t = window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <script>
        // Loader: full on the first page of a visit; afterwards only if a page is slow (> ~0.5s).
        (function () {
            var root = document.documentElement, t0 = Date.now(), first = true;
            try { first = sessionStorage.getItem('athariyah-loaded') !== '1'; } catch (e) {}
            if (!first) root.classList.add('loader-late');
            function done() {
                var el = document.getElementById('site-loader');
                if (!el || el.classList.contains('is-done')) return;
                // on the first visit let the orb finish at least one lap
                var wait = first ? Math.max(0, 1100 - (Date.now() - t0)) : 0;
                setTimeout(function () {
                    el.classList.add('is-done');
                    root.classList.add('is-loaded');
                    try { sessionStorage.setItem('athariyah-loaded', '1'); } catch (e) {}
                    setTimeout(function () { el.hidden = true; }, 700);
                }, wait);
            }
            window.addEventListener('load', done);
            window.addEventListener('pageshow', function (e) { if (e.persisted) done(); });
            setTimeout(done, 8000); // never block the page
        })();
    </script>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/motion.css') }}">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <script>document.documentElement.classList.replace('no-js','js')</script>
</head>
<body data-page="{{ $page ?? Route::currentRouteName() }}">
    <div class="scroll-progress" aria-hidden="true"></div>
    <div class="site-loader" id="site-loader" role="status" aria-label="جارٍ تحميل الصفحة">
        <div class="site-loader__stage" aria-hidden="true">
            <span class="site-loader__ring"></span>
            <span class="site-loader__trail"></span>
            <span class="site-loader__orbit"><span class="site-loader__orb"></span></span>
            <span class="site-loader__mark"></span>
        </div>
        <p class="site-loader__name" aria-hidden="true">{{ config('athariyah.name') }}</p>
    </div>

    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/motion.js') }}"></script>
    @stack('scripts')
</body>
</html>
