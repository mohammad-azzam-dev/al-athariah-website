@extends('layouts.app')

@section('title', 'المقرأة الأثرية')
@section('description', 'برامج القرآن الكريم والسنة النبوية للذكور والإناث: خيركم لتصحيح التلاوة، تاج الوقار وحلية القواوير لحفظ القرآن، وبرنامج الإمام الألباني لحفظ الأحاديث.')

@push('styles')<link rel="stylesheet" href="{{ asset('css/maqra.css') }}">@endpush

@php
    $q = $data['quran'];
    $s = $data['sunnah'];
    $c = $data['calc'];
    $star = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="14" height="14"/><rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/></svg>';
@endphp

@section('content')
    <section class="page-hero">
        <div class="container">
            <ol class="breadcrumb" data-anim="fade-down"><li><a href="{{ route('home') }}">الرئيسية</a></li><li>{{ $data['hero']['title'] }}</li></ol>
            <h1 data-anim="fade-up" data-delay="80">{{ $data['hero']['title'] }}</h1>
            <p data-anim="fade-up" data-delay="160">{{ $data['hero']['lead'] }}</p>
            <div class="page-hero__actions" data-anim="fade-up" data-delay="240">
                <a class="btn btn--primary" href="#calc">احسب وتيرة حفظك</a>
                <a class="btn btn--ghost" href="{{ route('path') }}">مسار الطالب</a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="tabs" role="tablist" data-tabs aria-label="أقسام المقرأة" data-anim="fade-up">
                @foreach ($data['tabs'] as $i => $tab)
                    <button type="button" role="tab" id="tab-{{ $tab['id'] }}" aria-controls="panel-{{ $tab['id'] }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}">{{ $tab['label'] }}</button>
                @endforeach
            </div>

            {{-- ================= القرآن ================= --}}
            <div role="tabpanel" id="panel-quran" aria-labelledby="tab-quran" class="tabpanel">
                <div class="section__head" data-anim="fade-up">
                    <span class="eyebrow">{{ $q['eyebrow'] }}</span>
                    <h2>{{ $q['title'] }}</h2>
                </div>

                <article class="card prog" data-anim="zoom" data-tilt>
                    <span class="badge badge--mint">{{ $q['khayrukum']['badge'] }}</span>
                    <h3>{{ $q['khayrukum']['title'] }} <small>{{ $q['khayrukum']['sub'] }}</small></h3>
                    <ul class="prog__list">
                        @foreach ($q['khayrukum']['items'] as $item)<li>{{ $item }}</li>@endforeach
                    </ul>
                </article>

                <article class="card prog" data-anim="rise">
                    <span class="badge">{{ $q['taj']['badge'] }}</span>
                    <h3>{{ $q['taj']['title'] }}</h3>
                    <p>{!! $q['taj']['duration'] !!}</p>

                    @foreach ($q['taj']['tables'] as $t)
                        <h4 data-anim="fade-right">{{ $t['title'] }} @if ($t['sub'])<small>{{ $t['sub'] }}</small>@endif</h4>
                        <div class="table-wrap" data-anim="flip"><table class="table">
                            <thead><tr>@foreach ($t['head'] as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
                            <tbody>@foreach ($t['rows'] as $row)<tr>@foreach ($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>@endforeach</tbody>
                        </table></div>
                    @endforeach
                </article>
            </div>

            {{-- ================= السنة ================= --}}
            <div role="tabpanel" id="panel-sunnah" aria-labelledby="tab-sunnah" class="tabpanel" hidden>
                <div class="section__head" data-anim="fade-up">
                    <span class="eyebrow">{{ $s['eyebrow'] }}</span>
                    <h2>{{ $s['title'] }}</h2>
                </div>

                <article class="card prog" data-anim="rise">
                    <span class="badge">{{ $s['albani']['badge'] }}</span>
                    <h3>{{ $s['albani']['title'] }}</h3>
                    <p>{!! $s['albani']['duration'] !!}</p>

                    <h4 data-anim="fade-right">{{ $s['albani']['materials_title'] }}</h4>
                    <div class="table-wrap" data-anim="flip"><table class="table">
                        <thead><tr><th>{{ $s['albani']['materials_title'] }}</th></tr></thead>
                        <tbody>@foreach ($s['albani']['materials'] as $m)<tr><td class="book">{{ $m }}</td></tr>@endforeach</tbody>
                    </table></div>
                    <p class="total" data-anim="zoom"><span>{{ $s['albani']['total_label'] }}</span> <strong><span data-count="{{ $s['albani']['total_count'] }}" data-sep>٤٬٣٢٣</span> حديث</strong></p>

                    <h4 data-anim="fade-right">{{ $s['albani']['table']['title'] }}</h4>
                    <div class="table-wrap" data-anim="flip"><table class="table">
                        <thead><tr>@foreach ($s['albani']['table']['head'] as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
                        <tbody>@foreach ($s['albani']['table']['rows'] as $row)<tr>@foreach ($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>@endforeach</tbody>
                    </table></div>
                </article>
            </div>
        </div>
    </section>

    {{-- ================= الحاسبة ================= --}}
    <section class="section section--dark section--pattern" id="calc">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">{{ $c['eyebrow'] }}</span>
                <h2>{{ $c['title'] }}</h2>
                <p>{{ $c['lead'] }}</p>
            </div>

            <form class="calc" id="calc-form" novalidate data-anim="zoom">
                <div class="calc__fields">
                    <div class="field">
                        <label for="calc-prog">البرنامج</label>
                        <select id="calc-prog">
                            @foreach ($c['options'] as $val => $label)<option value="{{ $val }}">{{ $label }}</option>@endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="calc-date">تاريخ البداية</label>
                        <input type="date" id="calc-date">
                    </div>
                </div>

                <div class="calc__out" id="calc-out" aria-live="polite">
                    <div class="calc__stats">
                        <div class="calc__stat"><span class="calc__label">يوميًا</span><strong id="out-day">—</strong></div>
                        <div class="calc__stat"><span class="calc__label">أسبوعيًا</span><strong id="out-week">—</strong></div>
                        <div class="calc__stat"><span class="calc__label">شهريًا</span><strong id="out-month">—</strong></div>
                    </div>
                    <div class="calc__bar" aria-hidden="true"><span id="out-bar"></span></div>
                    <p class="calc__finish">الانتهاء المتوقع تقريبًا: <strong id="out-finish">—</strong></p>
                    <p class="calc__note" id="out-note"></p>
                </div>
                <p class="calc__disclaimer">{{ $c['disclaimer'] }}</p>
            </form>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="note" data-anim="fade-up">
                <p>{{ $data['note']['text'] }} <a href="{{ $data['note']['url'] }}" target="_blank" rel="noopener">{{ $data['note']['url_label'] }}</a></p>
                <p><a class="btn btn--primary btn--sm" href="{{ route('register') }}">سجّل الآن</a> <a class="btn btn--ghost btn--sm" href="{{ route('matun') }}">قسم المتون العلمية</a></p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>window.MAQRA_PROGRAMS = @json($c['programs']);</script>
<script src="{{ asset('js/maqra.js') }}"></script>
@endpush
