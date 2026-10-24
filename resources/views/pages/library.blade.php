@extends('layouts.app')

@section('title', 'المكتبة الأثرية')
@section('description', 'فهرس قابل للبحث لكل كتاب ومتن ومقرر في مناهج المدرسة والكلية والمتون: ابحث بالعنوان أو المؤلف أو المادة، واعرف أين يُدرَّس.')

@push('styles')<link rel="stylesheet" href="{{ asset('css/library.css') }}">@endpush

@php
    $groups = collect($data['groups'])->keyBy('id');
    $progs = collect($data['programmes'])->keyBy('id');
    $total = count($data['records']);
    $arNum = fn ($n) => strtr((string) $n, '0123456789', '٠١٢٣٤٥٦٧٨٩');
    $star = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="14" height="14"/><rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/></svg>';
    $js = [
        'groups' => $data['groups'],
        'programmes' => collect($data['programmes'])->map(fn ($p) => $p + ['page' => route($p['route'])])->all(),
        'records' => $data['records'],
    ];
@endphp

@section('content')
    <section class="page-hero lib-hero">
        <div class="container">
            <ol class="breadcrumb" data-anim="fade-down"><li><a href="{{ route('home') }}">الرئيسية</a></li><li>المكتبة الأثرية</li></ol>
            <h1 data-anim="fade-up" data-delay="80">المكتبة الأثرية</h1>
            <p data-anim="fade-up" data-delay="180">فهرس لكل كتاب ومتن ومقرر يمرّ به الطالب في مساره. اكتب اسم الكتاب أو مؤلفه، واعرف أين يُدرَّس وبأي طريقة.</p>
            <form class="lib-search" role="search" id="lib-search-form" method="get" action="{{ route('library') }}" novalidate data-anim="zoom" data-delay="300">
                <label class="sr-only" for="lib-q">ابحث في المكتبة</label>
                <svg class="lib-search__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                <input id="lib-q" name="q" type="search" autocomplete="off" placeholder="ابحث بالعنوان أو المؤلف أو المادة…" enterkeyhint="search" value="{{ request('q') }}">
                <button class="lib-search__clear" type="button" id="lib-clear" aria-label="مسح البحث" hidden>×</button>
            </form>
        </div>
    </section>

    <section class="section lib-catalog" id="catalog" aria-labelledby="catalog-title">
        <div class="container">
            <h2 id="catalog-title" class="sr-only">فهرس الكتب</h2>

            <div class="lib-filters" data-anim="fade-up">
                <div class="lib-filters__row" role="group" aria-labelledby="f-prog">
                    <span class="lib-filters__label" id="f-prog">البرنامج</span>
                    <div class="lib-chips" id="chips-prog"></div>
                </div>
                <div class="lib-filters__row" role="group" aria-labelledby="f-field">
                    <span class="lib-filters__label" id="f-field">المجال</span>
                    <div class="lib-chips" id="chips-field"></div>
                </div>
            </div>

            <div class="lib-bar">
                <p class="lib-count" id="lib-count" role="status" aria-live="polite">عدد النتائج: {{ $arNum($total) }} من {{ $arNum($total) }}</p>
                <div class="lib-sort">
                    <label for="lib-sort">الترتيب</label>
                    <select id="lib-sort">
                        <option value="guide">حسب ترتيب الدليل</option>
                        <option value="title">العنوان (أبجديًا)</option>
                        <option value="field">المجال</option>
                        <option value="author">المؤلف</option>
                    </select>
                </div>
            </div>

            <div class="lib-grid" id="lib-grid">
                @foreach ($data['records'] as $r)
                    @php($g = $groups[$r['group']])
                    <button type="button" class="book" data-id="{{ $r['id'] }}" style="--h: {{ $g['hue'] }}; --i: {{ $loop->index % 10 }}" aria-haspopup="dialog"
                            aria-label="{{ $r['title'] }} — {{ $r['author'] ?: 'المؤلف غير مذكور' }} — {{ $progs[$r['prog']]['label'] }}">
                        <span class="book__field">{{ $r['field'] }}</span>
                        <span class="book__title">{{ $r['title'] }}</span>
                        <span class="book__author{{ $r['author'] ? '' : ' book__author--unknown' }}">{{ $r['author'] ?: 'المؤلف غير مذكور' }}</span>
                        <span class="book__prog">{{ $progs[$r['prog']]['label'] }}</span>
                    </button>
                @endforeach
            </div>

            <div class="lib-empty" id="lib-empty" hidden>
                <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M8 38V10a3 3 0 013-3h22a3 3 0 013 3v28M8 38a3 3 0 013-3h25M8 38a3 3 0 003 3h25V35"/><path d="M18 18l8 8M26 18l-8 8"/></svg>
                <h3>لا توجد نتائج مطابقة</h3>
                <p>جرّب كلمة أقصر، أو أزل بعض المرشحات.</p>
                <button type="button" class="btn btn--dark btn--sm" id="lib-reset">إعادة ضبط البحث</button>
            </div>
        </div>
    </section>

    <section class="section section--mint">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">كيف تخدمك المكتبة</span>
                <h2>دليلك إلى كتب المنهج</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>المكتبة الأثرية فهرس يجمع الكتب والمتون والمقررات المذكورة في الدليل الشامل في مكان واحد.</p>
            </div>
            <div class="grid grid--3" data-stagger="120">
                <article class="card card--glow" data-tilt>
                    <h3>اعرف ما ستدرسه</h3>
                    <p>لكل كتاب بطاقة تبيّن مؤلفه ومجاله والبرنامج الذي يُدرَّس فيه وطريقة دراسته (تفاعلي، دراسة ذاتية، حفظ…).</p>
                </article>
                <article class="card card--glow" data-tilt>
                    <h3>خطّط لمسارك</h3>
                    <p>تتبّع كتب كل برنامج: المدرسة، ثم الكلية، ثم المتون، وانتقل من البطاقة إلى صفحة البرنامج لتعرف المزيد.</p>
                </article>
                <article class="card card--glow" data-tilt>
                    <h3>ابحث بسهولة</h3>
                    <p>يتجاهل البحث التشكيل ويوحّد صور الهمزة والتاء المربوطة والياء، فتجد الكتاب سواء كتبته مشكولًا أو غير مشكول.</p>
                </article>
            </div>
            <p class="note lib-note" data-anim="fade-up"><strong>تنبيه:</strong> النسخ الرقمية للقراءة غير متاحة بعد، ولذلك لا توجد في هذه الصفحة روابط تحميل. المكتبة حاليًا فهرس تعريفي فقط. للاطلاع على المناهج والمقررات والتسجيل زر الموقع الرسمي <a href="https://alathariah.org" rel="noopener" target="_blank">alathariah.org</a>.</p>
        </div>
    </section>

    <dialog class="lib-dialog" id="lib-dialog" aria-labelledby="dlg-title">
        <div class="lib-dialog__inner">
            <button type="button" class="lib-dialog__close" id="dlg-close" aria-label="إغلاق">×</button>
            <div class="lib-dialog__head" id="dlg-head">
                <span class="lib-dialog__field" id="dlg-field"></span>
                <h2 id="dlg-title"></h2>
                <p class="lib-dialog__author" id="dlg-author"></p>
            </div>
            <dl class="lib-dialog__meta" id="dlg-meta"></dl>
            <div id="dlg-topics-wrap" hidden>
                <h3>المفردات المذكورة في الدليل</h3>
                <ul class="lib-dialog__topics" id="dlg-topics"></ul>
            </div>
            <div id="dlg-also-wrap" hidden>
                <h3>يُذكر أيضًا في</h3>
                <ul class="lib-dialog__also" id="dlg-also"></ul>
            </div>
            <p class="lib-dialog__nodl">النسخة الرقمية غير متاحة حاليًا.</p>
            <a class="btn btn--primary" id="dlg-link" href="{{ route('school') }}">صفحة البرنامج</a>
        </div>
    </dialog>
@endsection

@push('scripts')
<script>window.LIBRARY_DATA = @json($js, JSON_UNESCAPED_UNICODE);</script>
<script src="{{ asset('js/library.js') }}"></script>
@endpush
