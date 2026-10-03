/* مسار الطالب — scroll-drawn timeline, roadmap toggles + level finder (guide only; final placement by administration) */
(function () {
  'use strict';
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Roadmap ---------- */
  var items = Array.prototype.slice.call(document.querySelectorAll('.path__item'));
  var wrap = document.getElementById('pathWrap');
  var fillEl = document.getElementById('pathFill');

  items.forEach(function (item) {
    Array.prototype.forEach.call(item.querySelectorAll('.path__progs li'), function (li, i) { li.style.setProperty('--i', i); });
  });

  function setOpen(item, open) {
    var btn = item.querySelector('.path__toggle');
    btn.setAttribute('aria-expanded', String(open));
    item.classList.toggle('is-open', open);
  }

  items.forEach(function (item) {
    item.querySelector('.path__toggle').addEventListener('click', function () { setOpen(item, !item.classList.contains('is-open')); });
  });
  var expand = document.getElementById('expandAll');
  var collapse = document.getElementById('collapseAll');
  if (expand) expand.addEventListener('click', function () { items.forEach(function (i) { setOpen(i, true); }); });
  if (collapse) collapse.addEventListener('click', function () { items.forEach(function (i) { setOpen(i, false); }); });

  /* gold line draws itself as you scroll; arch markers pop when the line reaches them */
  function updateLine() {
    if (!wrap || !fillEl) return;
    var r = wrap.getBoundingClientRect();
    var line = wrap.querySelector('.path__line').getBoundingClientRect();
    var probe = window.innerHeight * 0.6;               // the "reading line" of the viewport
    var p = reduce ? 1 : Math.min(1, Math.max(0, (probe - line.top) / Math.max(1, line.height)));
    wrap.style.setProperty('--prog', p.toFixed(4));
    var reachedY = line.top + line.height * p;
    items.forEach(function (item) {
      var m = item.querySelector('.path__marker').getBoundingClientRect();
      if (reduce || m.top + 30 <= reachedY) item.classList.add('is-reached');
    });
  }
  var ticking = false;
  function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(function () { ticking = false; updateLine(); }); } }
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll);
  updateLine();

  function showLevel(n) {
    var item = document.getElementById('level-' + n);
    if (!item) return;
    items.forEach(function (i) { i.classList.remove('is-target'); });
    setOpen(item, true);
    item.classList.add('is-target', 'is-reached');
    var card = item.querySelector('.path__card');
    if (card) card.classList.add('is-visible');
    item.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'center' });
  }

  /* ---------- Level finder ---------- */
  var body = document.getElementById('finderBody');
  var countEl = document.getElementById('finderCount');
  var fill = document.getElementById('finderFill');
  var cfg = window.PATH_FINDER;
  if (!body || !cfg) return;

  var AR = ['٠', '١', '٢', '٣', '٤'];
  var LEVEL_NAME = cfg.levelNames;
  var LEVEL_LINKS = cfg.levelLinks;
  var QUESTIONS = cfg.questions;

  var answers = {};
  var step = 0;
  var history = [];

  function isActive(q) { return !q.whenStage || q.whenStage.indexOf(answers.stage) !== -1; }
  function activeQuestions() { return QUESTIONS.filter(isActive); }

  function levelFor(a) {
    if (a.stage === 'none') return 1;
    if (a.stage === 'sec') return 2;
    if (a.stage === 'dip') return 3;
    if (a.stage === 'spec') return 4;
    return 3; // qual: the guide states no later path for the qualifying diploma
  }

  function el(tag, cls, text) {
    var n = document.createElement(tag);
    if (cls) n.className = cls;
    if (text) n.textContent = text;
    return n;
  }

  /* swap content with a slide: old panel slides out, new one slides in. dir = 'next' | 'back' */
  function swap(build, dir, focusFirst) {
    var old = body.firstElementChild;
    function mount() {
      body.innerHTML = '';
      var slide = el('div', 'finder__slide' + (dir === 'back' ? ' finder__slide--back' : ''));
      build(slide);
      body.appendChild(slide);
      if (focusFirst) { var f = slide.querySelector('.finder__opt'); if (f) f.focus({ preventScroll: true }); }
    }
    if (old && !reduce && dir) {
      old.classList.add(dir === 'back' ? 'is-leaving-back' : 'is-leaving-next');
      setTimeout(mount, 200);
    } else mount();
  }

  function renderQuestion(dir, noFocus) {
    var qs = activeQuestions();
    var q = qs[step];
    countEl.textContent = 'السؤال ' + (AR[step + 1] || step + 1) + ' من ' + (AR[qs.length] || qs.length);
    fill.style.width = (step / qs.length * 100) + '%';

    swap(function (slide) {
      var fs = el('fieldset');
      fs.style.cssText = 'border:0;padding:0;margin:0;min-width:0';
      var lg = el('legend', 'finder__q h3', q.text);
      lg.style.cssText = 'padding:0;font-family:var(--font-head);font-weight:600;color:var(--green-800)';
      fs.appendChild(lg);
      var wrapOpts = el('div', 'finder__opts');
      q.opts.forEach(function (o) {
        var b = el('button', 'finder__opt', o.t);
        b.type = 'button';
        b.addEventListener('click', function () { choose(q, o.v); });
        wrapOpts.appendChild(b);
      });
      fs.appendChild(wrapOpts);
      slide.appendChild(fs);

      if (step > 0) {
        var nav = el('div', 'finder__nav');
        var back = el('button', 'btn btn--ghost btn--sm', 'السؤال السابق');
        back.type = 'button';
        back.addEventListener('click', function () { step = history.pop(); renderQuestion('back'); });
        nav.appendChild(back);
        slide.appendChild(nav);
      }
    }, dir, !noFocus);
  }

  function choose(q, v) {
    answers[q.id] = v;
    // clear answers of later questions that may no longer apply
    QUESTIONS.forEach(function (x) { if (!isActive(x)) delete answers[x.id]; });
    var qs = activeQuestions();
    history.push(step);
    if (step + 1 < qs.length) { step += 1; renderQuestion('next'); } else { renderResult(); }
  }

  function renderResult() {
    var a = answers;
    var lvl = levelFor(a);
    var recs = [];

    if (a.stage === 'qual') {
      recs.push('لم يذكر الدليل مسارًا لاحقًا للدبلوم التأهيلي ضمن المستويات الأربعة؛ راجع الإدارة لمعرفة الخطوة التالية.');
    }
    if (lvl === 1) recs.push('ابدأ ببرنامج «درجات»، ومعه برنامج جادة المتعلمين (١٥ متنًا) في قسم المتون.');
    if (lvl === 2) recs.push('دبلوم العلوم الشرعية للخرّيجين من الثانويات الشرعية المعتمدة، ومن شروط تخرجه إتمام برنامج نخبة المتعلمين.');
    if (lvl === 3 && a.stage === 'dip') {
      if (a.branch === 'spec') recs.push('اتجه إلى الدبلوم التخصصي: العقيدة، أو أصول الفقه، أو علوم القرآن وعلوم التفسير.');
      else if (a.branch === 'qual') recs.push('اتجه إلى الدبلوم التأهيلي: التفسير، أو التربية الإسلامية، أو التاريخ، أو اللغة العربية.');
      else recs.push('عند هذا المستوى يمكنك الاختيار بين الدبلوم التخصصي والدبلوم التأهيلي.');
    }
    if (lvl === 3) recs.push('من شروط التخرج في هذا المستوى إتمام برنامج الإمام الألباني في قسم السنة (حفظ الأحاديث النبوية).');
    if (lvl === 4) recs.push('الدبلوم المسلكي: الأديان والفرق لخرّيجي التخصصي في العقيدة، أو الفقه المقارن لخرّيجي التخصصي في أصول الفقه، ومن شروط تخرجه إتمام برنامج صفوة المتعلمين.');

    if (a.quran === 'lt10') recs.push('يُشترط حفظ ١٠ أجزاء من القرآن الكريم للتخرج من كل مستوى، فابدأ حفظك في المقرأة.');
    else recs.push('حفظك لـ ١٠ أجزاء فأكثر يوافق شرط التخرج من كل مستوى.');
    if (a.goal === 'full') recs.push('لحفظ القرآن كاملًا: برنامج تاج الوقار (الذكور) / حلية القواربر (الإناث)، مدّته ٣ سنوات (شهرين سنويًا استدراك ومراجعة) أو ٦ سنوات بنصف المقادير.');
    if (a.goal === 'recite') recs.push('لتصحيح التلاوة: برنامج «خيركم» ومجلساه الأسبوعيان في المقرأة.');

    countEl.textContent = 'اكتمل التقييم';
    fill.style.width = '100%';

    swap(function (slide) {
      var box = el('div', 'finder__result');
      box.appendChild(el('div', 'finder__badge', AR[lvl]));
      box.appendChild(el('h3', '', 'نقطة انطلاقك المقترحة'));
      box.appendChild(el('p', '', LEVEL_NAME[lvl]));
      var ul = el('ul', 'finder__recs');
      recs.forEach(function (r, i) { var li = el('li', '', r); li.style.setProperty('--i', i); ul.appendChild(li); });
      box.appendChild(ul);
      box.appendChild(el('p', 'note', 'هذه نتيجة استرشادية؛ التحديد النهائي للمستوى تقرره الإدارة عبر alathariah.org.'));

      var actions = el('div', 'finder__actions');
      var show = el('button', 'btn btn--dark', 'اعرض هذا المستوى في المسار');
      show.type = 'button';
      show.addEventListener('click', function () { showLevel(lvl); });
      actions.appendChild(show);
      LEVEL_LINKS[lvl].forEach(function (l) {
        var a2 = el('a', 'btn btn--ghost', l[1]);
        a2.href = l[0];
        actions.appendChild(a2);
      });
      var reg = el('a', 'btn btn--primary', 'التسجيل والتواصل');
      reg.href = cfg.registerUrl;
      actions.appendChild(reg);
      var again = el('button', 'btn btn--ghost', 'أعد التقييم');
      again.type = 'button';
      again.addEventListener('click', restart);
      actions.appendChild(again);
      box.appendChild(actions);
      slide.appendChild(box);
    }, 'next');
  }

  function restart() {
    answers = {}; step = 0; history = [];
    renderQuestion('back');
  }

  answers = {}; step = 0; history = [];
  renderQuestion(null, true);
})();
