@extends('layouts.app')

@section('title', 'مدرسة وكلية ومكتبة في مكان واحد')
@section('description', 'مجمع دار العلوم الأثرية — التعليم الشرعي الرقمي التفاعلي: المدرسة الأثرية، الكلية الأثرية، المقرأة، المتون العلمية والمكتبة. ابدأ رحلتك في طلب العلم.')

@push('styles')<link rel="stylesheet" href="{{ asset('css/home.css') }}">@endpush

@php
    $star = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="14" height="14"/><rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/></svg>';
    $marquee = ['التوحيد','العقيدة','الفقه','أصول الفقه','مصطلح الحديث','أصول التفسير','القواعد الفقهية','النحو','الصرف','البلاغة','الإملاء','التزكية','السيرة النبوية','التفسير','القرآن والتجويد','الفرق','أدب الطلب'];
@endphp

@section('content')
    <!-- ===== Hero ===== -->
    <section class="hero">
        <span class="hero__bg-star spin-slow" aria-hidden="true" data-parallax="0.12">
            <svg viewBox="0 0 200 200"><g fill="none" stroke="#C9A24B" stroke-width="1"><rect x="30" y="30" width="140" height="140"/><rect x="30" y="30" width="140" height="140" transform="rotate(45 100 100)"/><circle cx="100" cy="100" r="60"/></g></svg>
        </span>
        <div class="container hero__inner">
            <div class="hero__text">
                <span class="hero__tag" data-anim="fade-down">قسم التعليم عن بُعد · ١٤٤٧هـ</span>
                <h1 data-anim="fade-up" data-delay="100">رحلتك في <span class="hero__accent">طلب العلم</span> تبدأ من هنا</h1>
                <p data-anim="fade-up" data-delay="250">مدرسة، وكلية، ومكتبة، ومقرأة في مكان واحد. تعلَّم العلم الشرعي من أصوله، بخطة واضحة تأخذ بيدك من أول متن إلى التخصص — مباشرًا وتفاعليًا من بيتك.</p>
                <div class="hero__actions" data-anim="fade-up" data-delay="400">
                    <a class="btn btn--primary" href="{{ route('path') }}">ابدأ رحلتك</a>
                    <a class="btn btn--ghost" href="#houses">استكشف الأقسام</a>
                </div>
                <ul class="hero__facts" aria-label="لمحة سريعة" data-anim="fade-up" data-delay="550">
                    <li><strong data-count="4">٤</strong> مستويات</li>
                    <li><strong data-count="26">٢٦</strong> مادة في «درجات»</li>
                    <li><strong data-count="10">١٠</strong> أجزاء حفظ لكل مستوى</li>
                </ul>
            </div>
            <div class="hero__art" aria-hidden="true" data-anim="zoom" data-delay="300">
                <div class="hero__arch">
                    <svg viewBox="0 0 200 200" class="hero__star float--slow float">
                        <g fill="none" stroke="#C9A24B" stroke-width="1.4">
                            <rect x="40" y="40" width="120" height="120" class="draw"/>
                            <rect x="40" y="40" width="120" height="120" transform="rotate(45 100 100)" class="draw"/>
                            <circle cx="100" cy="100" r="34" class="draw"/><circle cx="100" cy="100" r="52" stroke-dasharray="3 5"/>
                        </g>
                        <text x="100" y="124" text-anchor="middle" font-family="Reem Kufi, sans-serif" font-size="74" font-weight="700" fill="#FAF6EC">أثر</text>
                    </svg>
                </div>
                <span class="hero__orb hero__orb--1 float">مدرسة</span>
                <span class="hero__orb hero__orb--2 float float--slow">كلية</span>
                <span class="hero__orb hero__orb--3 float">مكتبة</span>
            </div>
        </div>
    </section>

    <!-- ===== Subjects marquee ===== -->
    <div class="marquee-band" aria-hidden="true">
        <div class="marquee"><div class="marquee__track">
            @foreach (array_merge($marquee, $marquee) as $w)<span class="marquee__item">{{ $w }}</span>@endforeach
        </div></div>
    </div>

    <!-- ===== Three houses ===== -->
    <section class="section" id="houses">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">ثلاثة بيوت للعلم</span>
                <h2>اختر بيتك الأول</h2>
                <div class="ornament" aria-hidden="true">{!! $star !!}</div>
                <p>كل قسم له صفحته وبرنامجه، وكلها تتكامل في مسار واحد من المرحلة الأولى حتى التخصص.</p>
            </div>

            <div class="grid grid--3 houses" data-stagger="140">
                <a class="card house card--glow" data-tilt href="{{ route('school') }}">
                    <div class="house__arch house__arch--school" aria-hidden="true">
                        <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M32 10 6 24l26 14 26-14-26-14Z"/><path d="M16 31v14c0 4 7 8 16 8s16-4 16-8V31"/><path d="M58 24v18"/></svg>
                    </div>
                    <span class="badge badge--mint">البداية</span>
                    <h3>المدرسة الأثرية</h3>
                    <p>برنامج «درجات»: تدرُّج علمي في ٢٦ مادة — التوحيد والفقه والحديث واللغة — لخريجي الثانويات وغيرهم.</p>
                    <span class="house__more">تعرّف على المدرسة ←</span>
                </a>
                <a class="card house card--glow" data-tilt href="{{ route('college') }}">
                    <div class="house__arch house__arch--college" aria-hidden="true">
                        <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 24 32 8l26 16"/><path d="M12 28h40M16 28v22M28 28v22M36 28v22M48 28v22M8 54h48"/></svg>
                    </div>
                    <span class="badge">التخصص</span>
                    <h3>الكلية الأثرية</h3>
                    <p>دبلوم العلوم الشرعية، ثم الدبلوم التخصصي أو التأهيلي، ثم المسلكي — بمقررات معتمدة ومجالس تفاعلية.</p>
                    <span class="house__more">استعرض الدبلومات ←</span>
                </a>
                <a class="card house card--glow" data-tilt href="{{ route('library') }}">
                    <div class="house__arch house__arch--library" aria-hidden="true">
                        <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10h10v44H10zM24 10h10v44H24z"/><path d="m40 14 9-3 9 38-9 3-9-38Z"/></svg>
                    </div>
                    <span class="badge badge--green">المرجع</span>
                    <h3>المكتبة الأثرية</h3>
                    <p>فهرس يجمع كتب ومتون المناهج كلها، مع بحث وتصنيف يوصلك إلى المقرر الذي تبحث عنه بسرعة.</p>
                    <span class="house__more">ابحث في المكتبة ←</span>
                </a>
            </div>

            <div class="grid grid--2 halls" data-stagger="140">
                <a class="card hall card--glow" href="{{ route('maqra') }}">
                    <h3>المقرأة الأثرية</h3>
                    <p>تصحيح التلاوة، وحفظ القرآن كاملًا، وحفظ الأحاديث النبوية على منهج الإمام الألباني.</p>
                </a>
                <a class="card hall card--glow" href="{{ route('matun') }}">
                    <h3>قسم المتون العلمية</h3>
                    <p>«صناعة المتعلّمين»: جادة المتعلمين، ثم نخبة المتعلمين، ثم صفوة المتعلمين.</p>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== Path preview ===== -->
    <section class="section section--dark section--pattern" id="journey">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">مسار الطالب</span>
                <h2>أربعة مستويات… طريق واحد</h2>
                <p>خطة تعليمية كاملة من المرحلة الابتدائية وصولًا إلى مرحلة التخصص بإذن الله.</p>
            </div>
            <ol class="levels" data-stagger="180">
                <li class="level" data-anim="rise"><span class="level__no">١</span><h3>المستوى الأول</h3><p>برنامج «درجات» + جادة المتعلمين + حفظ القرآن</p></li>
                <li class="level" data-anim="rise"><span class="level__no">٢</span><h3>المستوى الثاني</h3><p>دبلوم العلوم الشرعية + نخبة المتعلمين + حفظ القرآن</p></li>
                <li class="level" data-anim="rise"><span class="level__no">٣</span><h3>المستوى الثالث</h3><p>الدبلوم التخصصي / التأهيلي + برنامج الإمام الألباني</p></li>
                <li class="level" data-anim="rise"><span class="level__no">٤</span><h3>المستوى الرابع</h3><p>الدبلوم المسلكي + صفوة المتعلمين</p></li>
            </ol>
            <p class="center" data-anim="fade-up"><a class="btn btn--primary" href="{{ route('path') }}">اكتشف مسارك — أين أبدأ؟</a></p>
        </div>
    </section>

    <!-- ===== Library search teaser ===== -->
    <section class="section section--mint">
        <div class="container finder">
            <div class="finder__text" data-anim="fade-up">
                <span class="eyebrow">من المكتبة</span>
                <h2>عن أي كتاب تبحث؟</h2>
                <p>اكتب اسم كتاب أو علم أو مؤلف، وسنريك أين تدرسه ضمن برامج المجمع.</p>
            </div>
            <form class="finder__form" data-anim="zoom" action="{{ route('library') }}" method="get" role="search">
                <label class="sr-only" for="home-q">ابحث في المكتبة</label>
                <input id="home-q" name="q" type="search" placeholder="مثال: رياض الصالحين، الآجرومية، التوحيد…" autocomplete="off">
                <button class="btn btn--dark" type="submit">بحث</button>
            </form>
            <div class="finder__chips" data-stagger="80" aria-label="اقتراحات">
                @foreach (['التوحيد','الفقه','الحديث','النحو'] as $q)
                    <a class="chip" href="{{ route('library', ['q' => $q]) }}">{{ $q }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== Numbers from the guide ===== -->
    <section class="section">
        <div class="container">
            <div class="section__head" data-anim="fade-up">
                <span class="eyebrow">بالأرقام</span>
                <h2>برامج مصمَّمة بعناية</h2>
            </div>
            <div class="grid grid--4 stats" data-stagger="120">
                <div class="card stat"><span class="stat__num" data-count="635">٦٣٥</span><span class="stat__label">مجلسًا في سنتين</span></div>
                <div class="card stat"><span class="stat__num" data-count="4323" data-sep>٤٬٣٢٣</span><span class="stat__label">حديثًا في برنامج الإمام الألباني</span></div>
                <div class="card stat"><span class="stat__num" data-count="30">٣٠</span><span class="stat__label">جزءًا في حفظ القرآن كاملًا</span></div>
                <div class="card stat"><span class="stat__num" data-count="15">١٥</span><span class="stat__label">متنًا في جادة المتعلمين</span></div>
            </div>
        </div>
    </section>

    <!-- ===== Hadith + CTA ===== -->
    <section class="section section--paper">
        <div class="container">
            <blockquote class="quote" data-anim="flip">
                «مَنْ سَلَكَ طَرِيقًا يَلْتَمِسُ فِيهِ عِلْمًا سَهَّلَ اللَّهُ لَهُ بِهِ طَرِيقًا إِلَى الْجَنَّةِ»
                <cite>رواه أبو داود والترمذي من حديث أبي الدرداء رضي الله عنه</cite>
            </blockquote>
            <div class="cta" data-anim="fade-up">
                <h2>جاهز لتبدأ؟</h2>
                <p>اطّلع على شروط كل مستوى، ثم سجّل عبر موقع المجمع.</p>
                <div class="cta__actions">
                    <a class="btn btn--primary" href="{{ route('register') }}">كيف أسجّل؟</a>
                    <a class="btn btn--ghost" href="{{ config('athariyah.website') }}" target="_blank" rel="noopener">موقع المجمع alathariah.org</a>
                </div>
            </div>
        </div>
    </section>
@endsection
