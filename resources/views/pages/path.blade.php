@extends('layouts.app')

@section('title', 'مسار الطالب')
@section('description', 'خريطة تفاعلية لمسار الطالب في المجمع من المستوى الأول إلى الرابع، مع أداة «أين أبدأ؟» لاقتراح المستوى المناسب.')

@push('styles')<link rel="stylesheet" href="{{ asset('css/path.css') }}">@endpush

@php
    $star = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="14" height="14"/><rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/></svg>';
    $finder = $data['finder'];
    $linkMap = [];
    foreach ($finder['level_links'] as $lvl => $links) {
        $linkMap[$lvl] = array_map(fn ($l) => [route($l[0]), $l[1]], $links);
    }
    $finderConfig = [
        'questions' => $finder['questions'],
        'levelNames' => $finder['level_names'],
        'levelLinks' => $linkMap,
        'registerUrl' => route('register'),
    ];
@endphp

@section('content')
    <section class="page-hero">
        <div class="container">
            <ol class="breadcrumb"><li><a href="{{ route('home') }}">الرئيسية</a></li><li>مسار الطالب</li></ol>
            <h1 data-anim="fade-up">مسار الطالب</h1>
            <p data-anim="fade-up" data-delay="120">{{ $data['hero']['text'] }}</p>
            <div class="page-hero__actions" data-anim="fade-up" data-delay="240">
                <a class="btn btn--primary" href="#finder">أين أبدأ؟</a>
                <a class="btn btn--ghost" href="#roadmap">استعرض المسار</a>
            </div>
        </div>
    </section>

    <section class="section" id="roadmap">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">الخريطة</span>
                <h2>أربعة مستويات، طريق واحد</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>{{ $data['roadmap_intro'] }}</p>
            </div>

            <div class="path-tools" data-anim="fade-up">
                <button type="button" class="chip" id="expandAll">فتح كل المستويات</button>
                <button type="button" class="chip" id="collapseAll">طيّ الكل</button>
            </div>

            <div class="path-wrap" id="pathWrap">
                <div class="path__line" aria-hidden="true"><span class="path__fill" id="pathFill"></span></div>
                <ol class="path" id="pathList">
                    @foreach ($data['levels'] as $lv)
                        <li class="path__item" id="level-{{ $lv['n'] }}" data-level="{{ $lv['n'] }}">
                            <span class="path__marker arch" aria-hidden="true">{{ $lv['ar'] }}</span>
                            <article class="path__card" data-anim="fade-up">
                                <button class="path__toggle" type="button" aria-expanded="false" aria-controls="panel-{{ $lv['n'] }}">
                                    <span class="path__label">{{ $lv['label'] }}</span>
                                    <span class="path__title">{{ $lv['title'] }}</span>
                                    <span class="path__chips">
                                        @foreach ($lv['chips'] as $chip)<span class="badge badge--mint">{{ $chip }}</span>@endforeach
                                    </span>
                                    <span class="path__chev" aria-hidden="true"></span>
                                </button>
                                <div class="path__panel" id="panel-{{ $lv['n'] }}">
                                    <div class="path__panel-inner">
                                        <ul class="path__progs">
                                            @foreach ($lv['programs'] as $p)
                                                <li>
                                                    <h3>{{ $p['title'] }}</h3>
                                                    @foreach ($p['paras'] as $para)<p>{{ $para }}</p>@endforeach
                                                    @foreach ($p['strong'] ?? [] as $s)<p><strong>{{ $s[0] }}</strong> {{ $s[1] }}</p>@endforeach
                                                    @isset($p['link'])<a class="btn btn--sm btn--dark" href="{{ route($p['link'][0]) }}">{{ $p['link'][1] }}</a>@endisset
                                                </li>
                                            @endforeach
                                        </ul>
                                        @isset($lv['note'])<p class="path__note">{{ $lv['note'] }}</p>@endisset
                                    </div>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="path__end" aria-hidden="true" data-anim="zoom">
                <svg class="path__end-star" viewBox="0 0 80 80"><g fill="none" stroke="#C9A24B" stroke-width="2"><rect x="16" y="16" width="48" height="48" class="draw"/><rect x="16" y="16" width="48" height="48" transform="rotate(45 40 40)" class="draw"/></g></svg>
                <p>{{ $data['end_verse'] }}</p>
            </div>
        </div>
    </section>

    <section class="section section--mint section--pattern" id="finder">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">أداة مساعدة</span>
                <h2>أين أبدأ؟</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>{{ $finder['intro'] }}</p>
            </div>

            <div class="finder card" id="finderBox" data-anim="zoom">
                <div class="finder__bar" aria-hidden="true"><span class="finder__fill" id="finderFill"></span></div>
                <p class="finder__count" id="finderCount" aria-live="polite"></p>
                <div class="finder__stage" id="finderBody" aria-live="polite"></div>
                <noscript><p>تحتاج هذه الأداة إلى تفعيل جافاسكربت. يمكنك استعراض المستويات أعلاه.</p></noscript>
            </div>

            <p class="finder__disclaimer note">{{ $finder['disclaimer'] }} <a href="{{ config('athariyah.website') }}" target="_blank" rel="noopener">alathariah.org</a>.</p>
        </div>
    </section>
@endsection

@push('scripts')
<script>window.PATH_FINDER = @json($finderConfig);</script>
<script src="{{ asset('js/path.js') }}"></script>
@endpush
