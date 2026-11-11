@php $current = Route::currentRouteName(); @endphp
<a class="skip-link" href="#main">تخطَّ إلى المحتوى</a>
<header class="site-header">
    <div class="container site-header__inner">
        @include('partials.brand')
        <nav class="nav" aria-label="القائمة الرئيسية">
            <ul class="nav__list">
                @foreach (config('athariyah.nav') as $key => [$route, $label])
                    <li><a class="nav__link" href="{{ route($route) }}" @if ($current === $route) aria-current="page" @endif>{{ $label }}</a></li>
                @endforeach
                <li class="nav__cta"><a class="btn btn--primary btn--sm" href="{{ route('register') }}" @if ($current === 'register') aria-current="page" @endif>سجّل الآن</a></li>
            </ul>
        </nav>
        <div class="site-header__tools">
        <button class="theme-btn" type="button" data-theme-toggle aria-pressed="false" aria-label="تفعيل الوضع الليلي" title="الوضع الليلي">
            <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
            <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4.2"/><path d="M12 2v2.2M12 19.8V22M4.2 4.2l1.6 1.6M18.2 18.2l1.6 1.6M2 12h2.2M19.8 12H22M4.2 19.8l1.6-1.6M18.2 5.8l1.6-1.6"/></svg>
        </button>
        <button class="menu-btn" type="button" aria-label="فتح القائمة" aria-expanded="false" aria-controls="drawer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
        </div>
    </div>
</header>
<div class="drawer" id="drawer" aria-hidden="true">
    <div class="drawer__backdrop" data-close></div>
    <div class="drawer__panel" role="dialog" aria-modal="true" aria-label="القائمة">
        <button class="drawer__close" type="button" aria-label="إغلاق القائمة" data-close>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
        <ul class="drawer__list">
            @foreach (config('athariyah.nav') as $key => [$route, $label])
                <li><a href="{{ route($route) }}" @if ($current === $route) aria-current="page" @endif>{{ $label }}</a></li>
            @endforeach
            <li><a href="{{ route('register') }}" @if ($current === 'register') aria-current="page" @endif>سجّل الآن</a></li>
        </ul>
    </div>
</div>
