<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title') | {{ config('athariyah.short_name') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Reem+Kufi:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-page admin-auth">
<div class="login-box">
    <div class="admin-auth__logo">
        <a href="{{ route('home') }}"><img src="{{ asset('images/brand/logo-light-800.png') }}" alt="{{ config('athariyah.name') }}"></a>
    </div>
    <div class="card card-outline admin-auth__card">
        <div class="card-header text-center"><h1 class="h4 mb-0 admin-title">@yield('heading')</h1></div>
        <div class="card-body login-card-body">
            @if (session('status'))
                <div class="alert alert-success small" role="alert">{{ session('status') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
    <p class="text-center mt-3 admin-auth__back"><a href="{{ route('home') }}"><i class="bi bi-arrow-right"></i> العودة إلى الموقع</a></p>
</div>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/js/adminlte.min.js') }}"></script>
</body>
</html>
