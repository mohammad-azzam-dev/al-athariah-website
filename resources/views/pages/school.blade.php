@extends('layouts.app')

@section('title', 'المدرسة الأثرية — برنامج درجات')
@section('description', 'المدرسة الأثرية: برنامج «درجات» العلمي التدريجي، مدته سنتان، ويؤهلك للالتحاق بدبلوم العلوم الشرعية في الكلية الأثرية.')

@push('styles')<link rel="stylesheet" href="{{ asset('css/school.css') }}">@endpush
@push('scripts')<script src="{{ asset('js/school.js') }}"></script>@endpush

@php
    $star = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="14" height="14"/><rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/></svg>';
    $dash = '--';
@endphp

@section('content')
    <section class="page-hero">
        <div class="container">
            <ol class="breadcrumb" data-anim="fade-down"><li><a href="{{ route('home') }}">الرئيسية</a></li><li>المدرسة الأثرية</li></ol>
            <h1 data-anim="fade-up" data-delay="100">المدرسة الأثرية</h1>
            <p data-anim="fade-up" data-delay="220">الثانوية العامة الشرعية في مجمع دار العلوم الأثرية. تبدأ بها رحلتك مع «برنامج درجات»، البرنامج العلمي التدريجي.</p>
            <div class="page-hero__actions" data-anim="fade-up" data-delay="340">
                <a class="btn btn--primary" href="{{ route('register') }}">سجّل الآن</a>
                <a class="btn btn--ghost" href="#subjects">استعرض المواد</a>
            </div>
        </div>
    </section>

    <section class="section school-glance">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">نظرة سريعة</span>
                <h2>برنامج «درجات» في أرقام</h2>
            </div>
            <div class="school-glance__grid" data-stagger="110">
                @foreach ($data['glance'] as $g)
                    <div class="card stat {{ !empty($g['wide']) ? 'school-glance__wide' : '' }}" data-anim="zoom">
                        <span class="stat__num" @isset($g['count']) data-count="{{ $g['count'] }}" @endisset>{{ $g['num'] }}</span>
                        <span class="stat__label">{{ $g['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--mint" id="about">
        <div class="container school-intro">
            <div data-anim="fade-right">
                <span class="eyebrow">لماذا هذا القسم؟</span>
                <h2>علمٌ شرعي يصل إليك أينما كنت</h2>
                @foreach ($data['intro'] as $p)<p>{{ $p }}</p>@endforeach
            </div>
            <div data-anim="fade-left" data-delay="150">
                <figure class="quote school-quote">
                    {{ $data['quote']['text'] }}
                    <cite>{{ $data['quote']['cite'] }}</cite>
                </figure>
            </div>
        </div>
    </section>

    <section class="section" id="subjects">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">المنهج</span>
                <h2>البرنامج العلمي التدريجي «درجات»</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>تصفّح المواد بحسب الفن، أو ابحث باسم المادة أو الكتاب أو المؤلف.</p>
            </div>

            <div class="school-tools" data-anim="fade-up">
                <div class="school-tools__search">
                    <label for="subject-search">ابحث في المواد</label>
                    <input id="subject-search" type="search" placeholder="مثال: التوحيد، النحو، ابن عثيمين" autocomplete="off">
                </div>
                <div class="school-tools__chips" role="group" aria-label="تصفية حسب الفن" id="field-chips">
                    <button type="button" class="chip" data-field="الكل" aria-pressed="true">الكل</button>
                    @foreach ($data['fields'] as $f)
                        <button type="button" class="chip" data-field="{{ $f }}" aria-pressed="false">{{ $f }}</button>
                    @endforeach
                </div>
                <div class="school-tools__view" role="group" aria-label="طريقة العرض">
                    <button type="button" class="chip" data-view="cards" aria-pressed="true">بطاقات</button>
                    <button type="button" class="chip" data-view="table" aria-pressed="false">جدول</button>
                </div>
                <p class="school-tools__count" id="result-count" role="status" aria-live="polite">عدد المواد المعروضة: {{ count($data['subjects']) }}</p>
            </div>

            <div id="subject-cards" class="school-cards" data-stagger="50">
                @foreach ($data['subjects'] as $sub)
                    <article class="card school-card" data-id="{{ $sub['n'] }}" data-field="{{ $sub['f'] }}">
                        <div class="school-card__top">
                            <span class="school-card__num" aria-label="رقم {{ $sub['n'] }}">{{ $sub['n'] }}</span>
                            <span class="badge badge--green">{{ $sub['f'] }}</span>
                        </div>
                        <h3>{{ $sub['s'] }}</h3>
                        @foreach ($sub['items'] as $it)
                            <div class="school-card__item">
                                <p class="school-card__book">{{ $it[0] }}</p>
                                <ul class="school-card__meta">
                                    @if ($it[1] !== $dash)<li><strong>المؤلف:</strong> {{ $it[1] }}</li>@endif
                                </ul>
                                <div class="school-card__tags">
                                    <span class="badge badge--mint">{{ $it[2] }}</span>
                                    @if ($it[3] !== '')<span class="badge">{{ $it[3] }}</span>@endif
                                </div>
                            </div>
                        @endforeach
                    </article>
                @endforeach
            </div>

            <div id="subject-table" class="school-table" hidden>
                <div class="table-wrap">
                    <table class="table school-table__table">
                        <caption>مواد برنامج «درجات»</caption>
                        <thead>
                            <tr><th scope="col">#</th><th scope="col">المادة</th><th scope="col">المقرر</th><th scope="col">المؤلف</th><th scope="col">طريقة الدراسة</th><th scope="col">ملاحظات</th></tr>
                        </thead>
                        @foreach ($data['subjects'] as $sub)
                            @php $n = count($sub['items']); @endphp
                            <tbody data-id="{{ $sub['n'] }}" data-field="{{ $sub['f'] }}">
                                @foreach ($sub['items'] as $idx => $it)
                                    <tr>
                                        @if ($idx === 0)
                                            <td class="num" @if ($n > 1) rowspan="{{ $n }}" @endif>{{ $sub['n'] }}</td>
                                            <th scope="row" class="school-table__subject" @if ($n > 1) rowspan="{{ $n }}" @endif>{{ $sub['s'] }}</th>
                                        @endif
                                        <td class="book">{{ $it[0] }}</td><td>{{ $it[1] }}</td><td>{{ $it[2] }}</td><td>{{ $it[3] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>

            <p class="school-empty" id="empty-msg" hidden>لا توجد مواد مطابقة لبحثك. جرّب كلمة أخرى أو اختر «الكل».</p>

            <p class="school-footnote" data-anim="zoom">{{ $data['footnote'] }}</p>
        </div>
    </section>

    <section class="section section--paper">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">الخطوة التالية</span>
                <h2>التخرج ومكانك في الرحلة</h2>
            </div>
            <div class="grid grid--2" data-stagger="150">
                <div class="card" data-anim="flip">
                    <h3>{{ $data['graduation']['title'] }}</h3>
                    <p>{{ $data['graduation']['lead'] }} <strong>{{ $data['graduation']['strong'] }}</strong> {{ $data['graduation']['tail'] }}</p>
                </div>
                <div class="card" data-anim="flip">
                    <h3>{{ $data['feeds']['title'] }}</h3>
                    <p>{{ $data['feeds']['text'] }}</p>
                </div>
            </div>
            <div class="school-levels">
                <h3 data-anim="fade-up">المستويات الأربعة</h3>
                <ol class="school-levels__list" data-stagger="130">
                    @foreach ($data['levels'] as $lv)
                        <li class="{{ !empty($lv['here']) ? 'is-here' : '' }}" data-anim="fade-right"><span class="badge {{ !empty($lv['here']) ? '' : 'badge--mint' }}">{{ $lv['badge'] }}</span> {{ $lv['text'] }}</li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <section class="section section--dark section--pattern">
        <div class="container school-cta" data-anim="zoom">
            <h2>ابدأ رحلتك من «درجات»</h2>
            <p>سجّل الآن، وتعرّف على مسارك الكامل من المدرسة إلى الكلية.</p>
            <div class="page-hero__actions school-cta__actions">
                <a class="btn btn--primary" href="{{ route('register') }}">سجّل الآن</a>
                <a class="btn btn--ghost" href="{{ route('path') }}">مسار الطالب</a>
            </div>
        </div>
    </section>
@endsection
