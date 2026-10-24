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
        <button class="menu-btn" type="button" aria-label="فتح القائمة" aria-expanded="false" aria-controls="drawer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
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
