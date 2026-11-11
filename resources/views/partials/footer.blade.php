<footer class="site-footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__brand">
                @include('partials.brand', ['light' => true])
                <p style="margin-top:1rem">«مَن سلَكَ طريقًا يلتَمِسُ فيهِ علمًا، سَهَّلَ اللَّهُ لَهُ طريقًا إلى الجنَّةِ».</p>
            </div>
            <div>
                <h3>الأقسام</h3>
                <ul>
                    <li><a href="{{ route('college') }}">الكلية الأثرية</a></li>
                    <li><a href="{{ route('school') }}">المدرسة الأثرية</a></li>
                    <li><a href="{{ route('maqra') }}">المقرأة الأثرية</a></li>
                    <li><a href="{{ route('library') }}">المكتبة الأثرية</a></li>
                </ul>
            </div>
            <div>
                <h3>للطالب</h3>
                <ul>
                    <li><a href="{{ route('path') }}">مسار الطالب</a></li>
                    <li><a href="{{ route('register') }}">التسجيل</a></li>
                    <li><a href="{{ route('register') }}#faq">الأسئلة الشائعة</a></li>
                </ul>
            </div>
            <div>
                <h3>تواصل معنا</h3>
                <ul>
                    <li>{{ config('athariyah.tagline') }}</li>
                    <li><a href="{{ route('register') }}#contact">نموذج الاستفسار</a></li>
                </ul>
            </div>
        </div>
        <div class="footer__bottom">
            <span>© {{ config('athariyah.name') }} — جميع الحقوق محفوظة</span>
            <span>نسأل الله أن يجعل هذا العمل خالصًا لوجهه الكريم</span>
        </div>
    </div>
</footer>
<button class="to-top" type="button" aria-label="العودة للأعلى">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 15l6-6 6 6"/></svg>
</button>
