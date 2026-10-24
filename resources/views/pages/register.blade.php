@extends('layouts.app')

@section('title', 'التسجيل والتواصل')
@section('description', 'خطوات الالتحاق بقسم التعليم عن بُعد في مجمع دار العلوم الأثرية، والمتطلبات لكل مستوى، والأسئلة الشائعة، والتسجيل عبر alathariah.org.')

@push('styles')<link rel="stylesheet" href="{{ asset('css/register.css') }}">@endpush

@php
    $star = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="14" height="14"/><rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/></svg>';
    $site = $data['site_url'];
    $req = $data['requirements'];
@endphp

@section('content')
    <section class="page-hero">
        <div class="container">
            <ol class="breadcrumb"><li><a href="{{ route('home') }}">الرئيسية</a></li><li>التسجيل والتواصل</li></ol>
            <h1 data-anim="fade-up">التسجيل والتواصل</h1>
            <p data-anim="fade-up" data-delay="120">{{ $data['hero'] }}</p>
            <div class="page-hero__actions" data-anim="fade-up" data-delay="240">
                <a class="btn btn--primary btn--lg" href="{{ $site }}" target="_blank" rel="noopener">سجّل عبر {{ $data['site_label'] }}</a>
                <a class="btn btn--ghost" href="{{ route('path') }}#finder">أين أبدأ؟</a>
            </div>
        </div>
    </section>

    <section class="section" id="how">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">كيف أنضم؟</span>
                <h2>أربع خطوات إلى مقعدك</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
            </div>
            <ol class="steps reg-steps" data-stagger="130">
                @foreach ($data['steps'] as $step)
                    <li class="card" data-tilt>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{!! $step['text'] !!}</p>
                        @if (!empty($step['links']))
                            <p>
                                @foreach ($step['links'] as $l)
                                    @php
                                        $href = $l[0] === 'external' ? $site : (str_contains($l[0], '#') ? route(explode('#', $l[0])[0]).'#'.explode('#', $l[0])[1] : route($l[0]));
                                        $ext = $l[0] === 'external';
                                    @endphp
                                    <a href="{{ $href }}" @if ($ext) target="_blank" rel="noopener" @endif>{{ $l[1] }}</a>@if (!$loop->last) · @endif
                                @endforeach
                            </p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="section section--mint section--pattern" id="requirements">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">المتطلبات</span>
                <h2>ماذا يلزمني في كل مستوى؟</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>{{ $req['note'] }}</p>
            </div>

            <div class="table-wrap reg-table" data-anim="rise">
                <table class="table">
                    <caption>{{ $req['caption'] }}</caption>
                    <thead>
                        <tr><th scope="col">المستوى</th><th scope="col">البرامج</th><th scope="col">القبول</th><th scope="col">من شروط التخرج</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($req['rows'] as $row)
                            <tr>
                                <th scope="row">{{ $row['level'] }}</th>
                                <td>{{ $row['programs'] }}</td>
                                <td>{{ $row['admission'] }}</td>
                                <td>{{ $row['graduation'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid grid--3 reg-facts" data-stagger="140">
                @foreach ($req['stats'] as $s)
                    <div class="card stat" data-anim="zoom"><span class="stat__num" data-count="{{ $s['num'] }}">{{ $s['text'] }}</span><span class="stat__label">{{ $s['label'] }}</span></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section" id="faq-section">
        <div class="container reg-narrow">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">الأسئلة الشائعة</span>
                <h2>أجوبة من الدليل</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
            </div>

            <div class="accordion" id="faq" data-stagger="70">
                @foreach ($data['faq'] as $item)
                    <details>
                        <summary>{{ $item['q'] }}</summary>
                        <div class="accordion__body">{!! $item['a'] !!}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--dark section--pattern" id="contact">
        <div class="container reg-narrow">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">استفسار</span>
                <h2>اكتب لنا استفسارك</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>جهّز استفسارك هنا، ثم أكمل التسجيل أو التواصل عبر موقع المجمع الرسمي.</p>
            </div>

            <div class="card reg-form-card" data-anim="flip">
                {{-- Front-end only: no action, no CSRF, nothing is sent anywhere. --}}
                <form id="inquiryForm" novalidate onsubmit="return false">
                    <div class="field">
                        <label for="fName">الاسم</label>
                        <input id="fName" name="name" type="text" autocomplete="name" required aria-describedby="eName">
                        <p class="field__err" id="eName" role="alert"></p>
                    </div>
                    <div class="field">
                        <label for="fLevel">المستوى المهتم به</label>
                        <select id="fLevel" name="level" required aria-describedby="eLevel">
                            <option value="">اختر المستوى</option>
                            @foreach ($data['form']['levels'] as $opt)<option>{{ $opt }}</option>@endforeach
                        </select>
                        <p class="field__err" id="eLevel" role="alert"></p>
                    </div>
                    <div class="field">
                        <label for="fMsg">استفسارك</label>
                        <textarea id="fMsg" name="message" rows="5" required aria-describedby="eMsg"></textarea>
                        <p class="field__err" id="eMsg" role="alert"></p>
                    </div>
                    <button class="btn btn--primary" type="submit">تجهيز الاستفسار</button>
                </form>

                <div class="reg-done" id="inquiryDone" role="status" tabindex="-1" hidden>
                    <h3>جاهز، تبقّت خطوة واحدة</h3>
                    <p>هذه الصفحة لا ترسل البيانات إلى أي جهة. لإرسال استفسارك أو إتمام التسجيل يرجى التواصل عبر موقع المجمع الرسمي.</p>
                    <blockquote class="reg-done__copy" id="inquiryCopy"></blockquote>
                    <div class="reg-done__actions">
                        <a class="btn btn--primary" href="{{ $site }}" target="_blank" rel="noopener">الذهاب إلى {{ $data['site_label'] }}</a>
                        <button class="btn btn--ghost" type="button" id="inquiryReset">كتابة استفسار آخر</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section reg-cta">
        <div class="container">
            <div class="card reg-cta__card" data-anim="zoom">
                <h2>ابدأ رحلتك اليوم</h2>
                <p>موقع المجمع هو المرجع للتسجيل والتواصل والاطلاع على المناهج والمقررات.</p>
                <a class="btn btn--primary btn--lg reg-cta__btn" href="{{ $site }}" target="_blank" rel="noopener">{{ $data['site_label'] }}</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')<script src="{{ asset('js/register.js') }}"></script>@endpush
