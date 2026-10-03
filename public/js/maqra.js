/* المقرأة — حاسبة وتيرة الحفظ (الأرقام من الدليل فقط) */
(function () {
  'use strict';

  // البيانات من MaqraData::get()['calc']['programs'] (تُمرَّر من الصفحة)
  var PROGS = window.MAQRA_PROGRAMS || {};

  var sel = document.getElementById('calc-prog');
  var dateEl = document.getElementById('calc-date');
  if (!sel || !dateEl) return;
  var out = {
    d: document.getElementById('out-day'), w: document.getElementById('out-week'),
    m: document.getElementById('out-month'), f: document.getElementById('out-finish'),
    n: document.getElementById('out-note'), bar: document.getElementById('out-bar')
  };
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  function pop(el, text) {
    if (el.textContent === text) return;
    el.textContent = text;
    if (reduce) return;
    el.classList.remove('is-pop'); void el.offsetWidth; el.classList.add('is-pop');
  }

  var nf = new Intl.NumberFormat('ar-EG', { maximumFractionDigits: 1 });
  var df = new Intl.DateTimeFormat('ar-EG-u-ca-gregory', { year: 'numeric', month: 'long', day: 'numeric' });

  function qty(n, unit) {
    if (unit === 'صفحة') {
      if (n === 0.5) return 'نصف صفحة';
      if (n === 1) return 'صفحة واحدة';
      if (n === 2.5) return '٢٫٥ صفحة';
      if (n >= 3 && n <= 10) return nf.format(n) + ' صفحات';
      return nf.format(n) + ' صفحة';
    }
    if (n >= 3 && n <= 10) return nf.format(n) + ' أحاديث';
    return nf.format(n) + ' حديث';
  }

  function pad(n) { return (n < 10 ? '0' : '') + n; }
  function todayISO() { var t = new Date(); return t.getFullYear() + '-' + pad(t.getMonth() + 1) + '-' + pad(t.getDate()); }

  function addMonths(d, n) {
    var r = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    var day = r.getDate();
    r.setDate(1);
    r.setMonth(r.getMonth() + n);
    var last = new Date(r.getFullYear(), r.getMonth() + 1, 0).getDate();
    r.setDate(Math.min(day, last));
    return r;
  }

  function update() {
    var p = PROGS[sel.value];
    if (!p) return;
    var k = p.half ? 0.5 : 1;
    pop(out.d, qty(p.d * k, p.unit) + (p.unit === 'صفحة' && !p.half ? ' (الإثنين – الخميس)' : ''));
    pop(out.w, qty(p.w * k, p.unit));
    pop(out.m, qty(p.m * k, p.unit));
    if (out.bar) out.bar.style.width = Math.min(100, p.months / 72 * 100) + '%';
    out.n.textContent = p.note + (p.half ? ' المقادير هنا هي نصف مقادير البرنامج الأقصر.' : '');
    var v = dateEl.value && dateEl.value.split('-');
    if (v && v.length === 3) {
      var start = new Date(+v[0], +v[1] - 1, +v[2]);
      pop(out.f, df.format(addMonths(start, p.months)) + ' م (تقدير تقريبي)');
    } else {
      pop(out.f, 'اختر تاريخ البداية');
    }
  }

  dateEl.value = todayISO();
  sel.addEventListener('change', update);
  dateEl.addEventListener('input', update);
  dateEl.addEventListener('change', update);
  document.getElementById('calc-form').addEventListener('submit', function (e) { e.preventDefault(); });
  update();
})();
