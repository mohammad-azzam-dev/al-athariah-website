<a class="brand" href="{{ route('home') }}" aria-label="{{ config('athariyah.name') }} — الصفحة الرئيسية">
    <svg class="brand__mark" viewBox="0 0 48 48" aria-hidden="true">
        <rect width="48" height="48" rx="14" fill="#1E3A2C"/>
        <g class="brand__star" fill="none" stroke="#C9A24B" stroke-width="1.6">
            <rect x="10" y="10" width="28" height="28"/>
            <rect x="10" y="10" width="28" height="28" transform="rotate(45 24 24)"/>
        </g>
        <text x="24" y="31" text-anchor="middle" font-family="Reem Kufi, sans-serif" font-size="20" font-weight="700" fill="#FAF6EC">أ</text>
    </svg>
    <span class="brand__text">
        <span class="brand__name">{{ config('athariyah.short_name') }}</span>
        <span class="brand__sub">{{ config('athariyah.tagline') }}</span>
    </span>
</a>
