{{-- Official logo. $light = true on dark backgrounds (footer) uses the light-coloured version. --}}
@php $light = $light ?? false; @endphp
<a class="brand" href="{{ route('home') }}" aria-label="{{ config('athariyah.name') }} — الصفحة الرئيسية">
    <img class="brand__logo"
         src="{{ asset($light ? 'images/brand/logo-light-800.png' : 'images/brand/logo-800.png') }}"
         srcset="{{ asset($light ? 'images/brand/logo-light-800.png' : 'images/brand/logo-800.png') }} 800w, {{ asset($light ? 'images/brand/logo-light.png' : 'images/brand/logo.png') }} 1590w"
         sizes="(max-width: 640px) 150px, 200px"
         width="800" height="248" alt="{{ config('athariyah.name') }}">
</a>
