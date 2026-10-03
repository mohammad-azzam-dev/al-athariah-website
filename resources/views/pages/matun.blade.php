@extends('layouts.app')

@section('title', 'المتون العلمية')
@section('description', 'قسم المتون العلمية «صناعة المتعلّمين» (للذكور والإناث): جادّة المتعلّمين، ونخبة المتعلّمين، وصفوة المتعلّمين — ثلاثة مستويات لحفظ المتون واختبار شروحها.')

@push('styles')<link rel="stylesheet" href="{{ asset('css/matun.css') }}">@endpush

@php
    $star = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="14" height="14"/><rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/></svg>';
    $anims = ['fade-right', 'zoom', 'fade-left'];
@endphp

@section('content')
    <section class="page-hero">
        <div class="container">
            <ol class="breadcrumb" data-anim="fade-down"><li><a href="{{ route('home') }}">الرئيسية</a></li><li>المتون العلمية</li></ol>
            <h1 data-anim="fade-up" data-delay="80">{{ $data['hero']['title'] }}</h1>
            <p data-anim="fade-up" data-delay="160">{{ $data['hero']['lead'] }}</p>
            <div class="page-hero__actions" data-anim="fade-up" data-delay="240">
                <a class="btn btn--primary" href="#ladder">ابدأ من الدرجة الأولى</a>
                <a class="btn btn--ghost" href="{{ route('path') }}">مسار الطالب</a>
            </div>
        </div>
    </section>

    <section class="section section--pattern" id="ladder">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">{{ $data['ladder']['eyebrow'] }}</span>
                <h2>{{ $data['ladder']['title'] }}</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>{{ $data['ladder']['lead'] }}</p>
                <button type="button" class="btn btn--ghost btn--sm" id="toggle-all" data-state="open" hidden>طيّ الكل</button>
            </div>

            <ol class="ladder">
                @foreach ($data['levels'] as $i => $lv)
                    @php $k = $i + 1; @endphp
                    <li class="ladder__step ladder__step--{{ $k }}" data-anim="{{ $anims[$i] }}" data-delay="{{ $i * 80 }}">
                        <article class="level card" data-tilt>
                            <header class="level__head">
                                <span class="level__num" aria-hidden="true">{{ $lv['n'] }}</span>
                                <div>
                                    <span class="badge">{{ $lv['badge'] }}</span>
                                    <h3>{{ $lv['title'] }}</h3>
                                    <p class="level__desc">{{ $lv['desc'] }}</p>
                                </div>
                            </header>
                            <div class="level__tools">
                                <button type="button" class="btn btn--dark btn--sm" data-toggle aria-expanded="true" aria-controls="texts-{{ $k }}" data-count-label="{{ $lv['count_label'] }}" hidden>إخفاء المتون ({{ $lv['count_label'] }})</button>
                            </div>
                            <div id="texts-{{ $k }}" class="level__body">
                                <div class="level__inner">
                                    <h4 class="sr-only">المتون المقررة</h4>
                                    <ol class="text-grid" data-stagger="50">
                                        @foreach ($lv['texts'] as $j => $t)
                                            <li class="text-card"><span class="text-card__n">{{ $j + 1 }}</span><span class="text-card__t">{{ $t }}</span></li>
                                        @endforeach
                                    </ol>
                                    <p class="quran-note">{{ $data['quran_note'] }}</p>
                                </div>
                            </div>
                            <footer class="level__foot">
                                <p>{{ $lv['foot'] }}</p>
                                <div class="level__links">
                                    <a class="btn btn--primary btn--sm" href="{{ route('path') }}">موضعه في مسار الطالب</a>
                                    <a class="btn btn--ghost btn--sm" href="{{ route($lv['link']['route']) }}">{{ $lv['link']['label'] }}</a>
                                </div>
                            </footer>
                        </article>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="section section--mint">
        <div class="container">
            <div class="note" data-anim="fade-up">
                <p>{{ $data['note']['text'] }} <a href="{{ $data['note']['url'] }}" target="_blank" rel="noopener">{{ $data['note']['url_label'] }}</a></p>
                <p><a class="btn btn--primary btn--sm" href="{{ route('register') }}">سجّل الآن</a> <a class="btn btn--ghost btn--sm" href="{{ route('maqra') }}">المقرأة الأثرية</a></p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')<script src="{{ asset('js/matun.js') }}"></script>@endpush
