<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'لوحة التحكم') | {{ config('athariyah.short_name') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Reem+Kufi:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="إظهار/إخفاء القائمة"><i class="bi bi-list"></i></a>
                </li>
                <li class="nav-item d-none d-md-block"><a href="{{ route('home') }}" class="nav-link" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-left me-1"></i> عرض الموقع</a></li>
                <li class="nav-item d-none d-md-block"><a href="{{ route('library') }}" class="nav-link" target="_blank" rel="noopener"><i class="bi bi-book me-1"></i> المكتبة العامة</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <span class="admin-avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header admin-user-header">
                            <span class="admin-avatar admin-avatar--lg">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                            <p>{{ auth()->user()->name }}<small>{{ auth()->user()->email }}</small></p>
                        </li>
                        <li class="user-footer d-flex justify-content-between">
                            <a href="{{ route('admin.profile') }}" class="btn btn-default btn-flat">الملف الشخصي</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button type="submit" class="btn btn-default btn-flat">تسجيل الخروج</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <aside class="app-sidebar shadow admin-sidebar" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <img src="{{ asset('images/brand/mark-light.png') }}" alt="" class="brand-image">
                <span class="brand-text">لوحة التحكم</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2" aria-label="قائمة لوحة التحكم">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i><p>الرئيسية</p>
                        </a>
                    </li>
                    <li class="nav-header">المكتبة</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.books.index') }}" class="nav-link {{ request()->routeIs('admin.books.index', 'admin.books.edit') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-bookmark"></i><p>الكتب</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.books.create') }}" class="nav-link {{ request()->routeIs('admin.books.create') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-plus-circle"></i><p>إضافة كتاب</p>
                        </a>
                    </li>
                    <li class="nav-header">الحساب</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-gear"></i><p>الملف الشخصي</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link" target="_blank" rel="noopener">
                            <i class="nav-icon bi bi-globe2"></i><p>الموقع</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6"><h3 class="mb-0 admin-title">@yield('title', 'لوحة التحكم')</h3></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                @if (session('status') && ! in_array(session('status'), ['profile-information-updated', 'password-updated'], true))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> يرجى تصحيح الحقول المشار إليها.
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <strong>{{ config('athariyah.name') }}</strong> — لوحة التحكم
        <div class="float-end d-none d-sm-inline">المكتبة الأثرية</div>
    </footer>
</div>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/js/adminlte.min.js') }}"></script>
<script>
    // Stagger entrance animations and count up the dashboard numbers.
    (function () {
        function stagger(sel) { document.querySelectorAll(sel).forEach(function (el, i) { el.style.setProperty('--adm-i', Math.min(i, 12)); }); }
        stagger('.app-content .card, .app-content .small-box'); stagger('.admin-sidebar .sidebar-menu > .nav-item'); stagger('.admin-table tbody tr');
        if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        document.querySelectorAll('[data-count]').forEach(function (el) {
            var end = parseInt(el.getAttribute('data-count'), 10) || 0, t0 = null;
            if (!end) return;
            function tick(t) { t0 = t0 || t; var p = Math.min((t - t0) / 900, 1); el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3))); if (p < 1) requestAnimationFrame(tick); }
            el.textContent = '0'; requestAnimationFrame(tick);
        });
    })();
    // Confirm destructive actions.
    document.addEventListener('submit', function (e) {
        var msg = e.target.getAttribute('data-confirm');
        if (msg && !window.confirm(msg)) e.preventDefault();
    });
</script>
@stack('scripts')
</body>
</html>
