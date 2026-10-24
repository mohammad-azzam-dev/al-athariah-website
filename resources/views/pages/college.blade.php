@extends('layouts.app')

@section('title', 'الكلية الأثرية')
@section('description', 'الكلية الأثرية: دبلوم العلوم الشرعية والدبلوم التخصصي والتأهيلي والمسلكي، موادها وتخصصاتها وشروط التخرج.')

@push('styles')<link rel="stylesheet" href="{{ asset('css/college.css') }}">@endpush
@push('scripts')<script src="{{ asset('js/college.js') }}"></script>@endpush

@php
    $star = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="14" height="14"/><rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/></svg>';
    $cells = fn ($v) => is_array($v) ? $v : [$v];
@endphp

@section('content')
    <section class="page-hero">
        <div class="container">
            <ol class="breadcrumb" data-anim="fade-down"><li><a href="{{ route('home') }}">الرئيسية</a></li><li>الكلية الأثرية</li></ol>
            <h1 data-anim="fade-up" data-delay="100">الكلية الأثرية</h1>
            <p data-anim="fade-up" data-delay="220">ثلاثة دبلومات متدرّجة في العلوم الشرعية، من دبلوم العلوم الشرعية إلى التخصص والتأهيل ثم المسلك.</p>
            <div class="page-hero__actions" data-anim="fade-up" data-delay="340">
                <a class="btn btn--primary" href="{{ route('register') }}">سجّل الآن</a>
                <a class="btn btn--ghost" href="{{ route('path') }}">اعرف مسارك</a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">نظرة عامة</span>
                <h2>مستويات الكلية</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>اختر الدبلوم لتتعرّف على موادّه وتخصصاته وشروط التخرج.</p>
            </div>
            <div class="grid grid--3 level-cards" data-stagger="130">
                @foreach ($data['levels'] as $lv)
                    <a class="card level-card card--glow" data-anim="zoom" data-tilt href="#diplomas" data-goto="{{ $lv['goto'] }}">
                        <span class="badge">{{ $lv['badge'] }}</span>
                        <h3>{{ $lv['title'] }}</h3>
                        <p>{{ $lv['text'] }}</p>
                        <span class="level-card__more" aria-hidden="true">عرض المواد ←</span>
                    </a>
                @endforeach
            </div>
            <p class="note college-note" data-anim="fade-up">{{ $data['qualifying_note'] }}</p>
        </div>
    </section>

    <section class="section section--mint" id="diplomas">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">المواد والتخصصات</span>
                <h2>الدبلومات</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
            </div>
            <div class="college-tools" data-anim="fade-up">
                <div class="tabs" role="tablist" data-tabs aria-label="الدبلومات">
                    @foreach ($data['diplomas'] as $dp)
                        <button role="tab" type="button" id="t{{ $dp['id'] }}" aria-controls="{{ $dp['id'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $dp['tab'] }}</button>
                    @endforeach
                </div>
                <div class="field college-filter">
                    <label for="subject-filter">ابحث في المواد الظاهرة</label>
                    <input type="search" id="subject-filter" placeholder="اكتب اسم مادة أو مؤلف أو كتاب" autocomplete="off">
                    <p class="college-filter__status" id="filter-status" role="status" aria-live="polite"></p>
                </div>
            </div>

            @foreach ($data['diplomas'] as $dp)
                <div role="tabpanel" id="{{ $dp['id'] }}" aria-labelledby="t{{ $dp['id'] }}" class="diploma-panel" @if (! $loop->first) hidden @endif>
                    <div class="panel-head"><span class="badge badge--green">{{ $dp['badge'] }}</span><h3>{{ $dp['title'] }}</h3></div>
                    @if ($dp['lead'])<p class="lead">{{ $dp['lead'] }}</p>@endif
                    <p class="college-grads">{{ $dp['grads'] }}</p>

                    @if ($dp['kind'] === 'table')
                        <div class="table-wrap" data-anim="flip"><table class="table">
                            <caption class="sr-only">مواد {{ $dp['title'] }}</caption>
                            <thead><tr><th class="num">#</th><th>المادة</th><th>المقرر</th><th>المؤلف</th><th>طريقة الدراسة</th><th>ملاحظة</th></tr></thead>
                            <tbody>
                                @foreach ($dp['subjects'] as $r)
                                    <tr>
                                        <td class="num">{{ $r['n'] }}</td>
                                        <td>{{ $r['subject'] }}</td>
                                        <td>@foreach ($cells($r['course']) as $c){{ $c }}@if (! $loop->last)<br>@endif @endforeach</td>
                                        <td>@foreach ($cells($r['author']) as $c){{ $c }}@if (! $loop->last)<br>@endif @endforeach</td>
                                        <td>@foreach ($cells($r['method']) as $c){{ $c }}@if (! $loop->last)<br>@endif @endforeach</td>
                                        <td>{{ $r['note'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table></div>
                        <p class="note college-note college-note--bar" data-anim="fade-right">{{ $dp['bar'] }}</p>
                    @else
                        <div class="tabs tabs--sub" role="tablist" data-tabs aria-label="{{ $dp['aria'] }}">
                            @foreach ($dp['specialties'] as $sp)
                                <button class="chip-tab" role="tab" type="button" id="t-{{ $sp['id'] }}" aria-controls="p-{{ $sp['id'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $sp['label'] }}</button>
                            @endforeach
                        </div>
                        @foreach ($dp['specialties'] as $sp)
                            <div role="tabpanel" id="p-{{ $sp['id'] }}" aria-labelledby="t-{{ $sp['id'] }}" class="subpanel" @if (! $loop->first) hidden @endif>
                                <h4 class="subpanel__title">{{ $sp['label'] }}</h4>
                                @if ($dp['kind'] === 'tables' && empty($sp['rows']))
                                    <p class="note college-note">{{ $sp['empty'] }}</p>
                                @elseif ($dp['kind'] === 'tables')
                                    <div class="table-wrap" data-anim="flip"><table class="table">
                                        <caption class="sr-only">مواد تخصص {{ $sp['label'] }}</caption>
                                        <thead><tr><th class="num">#</th><th>المادة</th><th>المؤلف</th><th>طريقة الدراسة</th></tr></thead>
                                        <tbody>
                                            @foreach ($sp['rows'] as $r)
                                                <tr><td class="num">{{ $r['n'] }}</td><td>{{ $r['subject'] }}</td><td>{{ $r['author'] }}</td><td>{{ $r['method'] }}</td></tr>
                                            @endforeach
                                        </tbody>
                                    </table></div>
                                @else
                                    <div class="table-wrap" data-anim="flip"><table class="table table--pairs">
                                        <caption class="sr-only">مواد {{ $sp['label'] }}</caption>
                                        <thead><tr><th colspan="2">المواد المقررة</th></tr></thead>
                                        <tbody>
                                            @foreach (array_chunk($sp['subjects'], 2) as $pair)
                                                <tr><td>{{ $pair[0] }}</td>@if (isset($pair[1]))<td>{{ $pair[1] }}</td>@else<td class="is-empty" aria-hidden="true"></td>@endif</tr>
                                            @endforeach
                                        </tbody>
                                    </table></div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    <p class="note college-note"><strong>من شروط التخرج:</strong> {{ $dp['graduation'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">نظام الدراسة</span>
                <h2>شروط التخرج ونظام الدراسة</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
            </div>
            <div class="grid grid--2">
                @foreach (['system' => 'fade-right', 'graduation' => 'fade-left'] as $key => $anim)
                    <div class="card" data-anim="{{ $anim }}">
                        <h3>{{ $data[$key]['title'] }}</h3>
                        <ul>@foreach ($data[$key]['items'] as $it)<li>{{ $it }}</li>@endforeach</ul>
                    </div>
                @endforeach
            </div>
            <div class="college-flow card" data-anim="rise">
                <h3>تسلسل المستويات في المجمع</h3>
                <ol class="flow" data-stagger="120">
                    @foreach ($data['flow'] as $f)
                        <li data-anim="fade-right"><strong>{{ $f[0] }}:</strong> {{ $f[1] }}</li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <section class="section section--dark section--pattern">
        <div class="container college-cta" data-anim="zoom">
            <h2>ابدأ رحلتك في الكلية الأثرية</h2>
            <p>سجّل الآن، أو اعرف مستواك ومسارك المناسب.</p>
            <div class="page-hero__actions">
                <a class="btn btn--primary" href="{{ route('register') }}">سجّل الآن</a>
                <a class="btn btn--ghost" href="{{ route('path') }}">مسار الطالب</a>
            </div>
        </div>
    </section>
@endsection
