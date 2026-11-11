{{-- Official logo. $light = true on always-dark areas (footer). The header carries both versions and theme.css shows the right one. --}}
@php
    $light = $light ?? false;
    $logo = fn ($v) => [
        'src' => asset("images/brand/logo{$v}-800.png"),
        'srcset' => asset("images/brand/logo{$v}-800.png").' 800w, '.asset("images/brand/logo{$v}.png").' 1590w',
    ];
@endphp
<a class="brand" href="{{ route('home') }}" aria-label="{{ config('athariyah.name') }} — الصفحة الرئيسية">
    @if ($light)
        @php $l = $logo('-light'); @endphp
        <img class="brand__logo" src="{{ $l['src'] }}" srcset="{{ $l['srcset'] }}" sizes="(max-width: 640px) 150px, 200px" width="800" height="248" alt="{{ config('athariyah.name') }}">
    @else
        @php $d = $logo(''); $l = $logo('-light'); @endphp
        <img class="brand__logo brand__logo--dark" src="{{ $d['src'] }}" srcset="{{ $d['srcset'] }}" sizes="(max-width: 640px) 150px, 200px" width="800" height="248" alt="{{ config('athariyah.name') }}">
        <img class="brand__logo brand__logo--light" src="{{ $l['src'] }}" srcset="{{ $l['srcset'] }}" sizes="(max-width: 640px) 150px, 200px" width="800" height="248" alt="" aria-hidden="true">
    @endif
</a>
