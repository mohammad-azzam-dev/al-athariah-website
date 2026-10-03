/* المكتبة الأثرية — بحث وتصفية وعرض التفاصيل (vanilla ES6) */
(function () {
  'use strict';
  var D = window.LIBRARY_DATA;
  if (!D) return;

  var $ = function (id) { return document.getElementById(id); };
  var grid = $('lib-grid'), empty = $('lib-empty'), count = $('lib-count'), input = $('lib-q'), clearBtn = $('lib-clear');
  var sortSel = $('lib-sort'), dlg = $('lib-dialog');
  var state = { q: '', prog: 'all', group: 'all', sort: 'guide' };

  var groupById = {}, progById = {};
  D.groups.forEach(function (g) { groupById[g.id] = g; });
  D.programmes.forEach(function (p) { progById[p.id] = p; });

  /* تطبيع عربي: حذف التشكيل والتطويل، توحيد الهمزات والتاء المربوطة والألف المقصورة */
  function norm(s) {
    return String(s || '')
      .replace(/[ؐ-ًؚ-ٰٟۖ-ۭـ]/g, '')
      .replace(/[أإآٱ]/g, 'ا')
      .replace(/ة/g, 'ه')
      .replace(/ى/g, 'ي')
      .replace(/\s+/g, ' ')
      .trim()
      .toLowerCase();
  }
  window.libNormalize = norm;

  D.records.forEach(function (r, i) {
    r.order = i;
    r.hay = norm([r.title, r.author || '', r.field, r.level, r.method, groupById[r.group].label, progById[r.prog].label, r.note, (r.topics || []).join(' ')].join(' '));
    r.ntitle = norm(r.title);
  });

  /* ---------- chips ---------- */
  function makeChips(host, items, key) {
    var all = [{ id: 'all', label: 'الكل' }].concat(items);
    all.forEach(function (it) {
      var b = document.createElement('button');
      b.type = 'button'; b.className = 'chip'; b.textContent = it.label; b.dataset.id = it.id;
      b.setAttribute('aria-pressed', String(state[key] === it.id));
      b.addEventListener('click', function () { state[key] = it.id; syncChips(host, key); render(); });
      host.appendChild(b);
    });
  }
  function syncChips(host, key) {
    Array.prototype.forEach.call(host.children, function (b) { b.setAttribute('aria-pressed', String(b.dataset.id === state[key])); });
  }
  makeChips($('chips-prog'), D.programmes.map(function (p) { return { id: p.id, label: p.label }; }), 'prog');
  makeChips($('chips-field'), D.groups.map(function (g) { return { id: g.id, label: g.label }; }), 'group');

  /* ---------- render ---------- */
  var collator = new Intl.Collator('ar');
  function sorted(list) {
    var l = list.slice();
    if (state.sort === 'title') l.sort(function (a, b) { return collator.compare(a.ntitle, b.ntitle); });
    else if (state.sort === 'field') l.sort(function (a, b) { return collator.compare(norm(groupById[a.group].label), norm(groupById[b.group].label)) || a.order - b.order; });
    else if (state.sort === 'author') l.sort(function (a, b) {
      if (!a.author !== !b.author) return a.author ? -1 : 1;
      return collator.compare(norm(a.author), norm(b.author)) || a.order - b.order;
    });
    else l.sort(function (a, b) { return a.order - b.order; });
    return l;
  }

  function matches(r) {
    if (state.prog !== 'all' && r.prog !== state.prog) return false;
    if (state.group !== 'all' && r.group !== state.group) return false;
    if (state.q) {
      var words = state.q.split(' ');
      for (var i = 0; i < words.length; i++) if (r.hay.indexOf(words[i]) === -1) return false;
    }
    return true;
  }

  /* ---------- render: cards are server-rendered; JS filters / reorders / animates them ---------- */
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var cards = {};
  Array.prototype.forEach.call(grid.querySelectorAll('.book'), function (el) { cards[el.dataset.id] = el; });
  grid.classList.add('lib-ready');
  var io = null, busy = 0, first = true;

  function enter(el, i) {
    el.classList.remove('is-out');
    el.style.setProperty('--d', (reduce ? 0 : Math.min(i, 14) * 45) + 'ms');
    el.classList.remove('is-in'); void el.offsetWidth; el.classList.add('is-in');
  }
  function reveal(list) {
    if (io) { io.disconnect(); io = null; }
    if (!('IntersectionObserver' in window) || reduce) { list.forEach(function (r) { enter(cards[r.id], 0); }); return; }
    var seq = 0;
    io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        io.unobserve(en.target); enter(en.target, seq++);
        setTimeout(function () { seq = Math.max(0, seq - 1); }, 60);
      });
    }, { threshold: 0.05, rootMargin: '0px 0px -4% 0px' });
    list.forEach(function (r) { var el = cards[r.id]; el.classList.remove('is-in'); io.observe(el); });
  }

  function render() {
    var list = sorted(D.records.filter(matches));
    var keep = {}; list.forEach(function (r) { keep[r.id] = true; });
    var token = ++busy;
    var leaving = D.records.filter(function (r) { return !keep[r.id] && !cards[r.id].hidden; });
    if (!first && !reduce) leaving.forEach(function (r) { cards[r.id].classList.add('is-out'); });

    function apply() {
      if (token !== busy) return;
      D.records.forEach(function (r) { if (!keep[r.id]) { cards[r.id].hidden = true; cards[r.id].classList.remove('is-out', 'is-in'); } });
      var frag = document.createDocumentFragment();
      list.forEach(function (r) { var el = cards[r.id]; el.hidden = false; frag.appendChild(el); });
      grid.appendChild(frag);
      empty.hidden = list.length !== 0;
      grid.hidden = list.length === 0;
      reveal(list); first = false;
    }
    if (leaving.length && !reduce && !first) setTimeout(apply, 170); else apply();

    count.textContent = list.length === 0 ? 'لا توجد نتائج'
      : 'عدد النتائج: ' + list.length.toLocaleString('ar-EG') + ' من ' + D.records.length.toLocaleString('ar-EG');
    clearBtn.hidden = !input.value;
  }

  /* ---------- search ---------- */
  var timer;
  input.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(function () { state.q = norm(input.value); render(); }, 120);
    clearBtn.hidden = !input.value;
  });
  $('lib-search-form').addEventListener('submit', function (e) { e.preventDefault(); state.q = norm(input.value); render(); $('catalog').scrollIntoView(); });
  clearBtn.addEventListener('click', function () { input.value = ''; state.q = ''; render(); input.focus(); });
  sortSel.addEventListener('change', function () { state.sort = sortSel.value; render(); });
  $('lib-reset').addEventListener('click', function () {
    state.q = ''; state.prog = 'all'; state.group = 'all'; input.value = '';
    syncChips($('chips-prog'), 'prog'); syncChips($('chips-field'), 'group'); render(); input.focus();
  });

  /* ---------- dialog ---------- */
  function row(dl, k, v) {
    var dt = document.createElement('dt'), dd = document.createElement('dd');
    dt.textContent = k; dd.textContent = v; dl.append(dt, dd);
  }
  function openDetail(id) {
    var r = D.records.filter(function (x) { return x.id === id; })[0];
    if (!r) return;
    var p = progById[r.prog];
    $('dlg-head').style.setProperty('--h', groupById[r.group].hue);
    $('dlg-field').textContent = r.field;
    $('dlg-title').textContent = r.title;
    $('dlg-author').textContent = r.author ? 'المؤلف: ' + r.author : 'المؤلف: غير مذكور في الدليل';
    var dl = $('dlg-meta'); dl.textContent = '';
    row(dl, 'المجال', groupById[r.group].label);
    row(dl, 'يُدرَس في', p.pageLabel);
    row(dl, 'المستوى', r.level);
    row(dl, 'طريقة الدراسة', r.method);
    if (r.note) row(dl, 'ملاحظة', r.note);

    var tw = $('dlg-topics-wrap'), tl = $('dlg-topics'); tl.textContent = '';
    tw.hidden = !(r.topics && r.topics.length);
    (r.topics || []).forEach(function (t) { var li = document.createElement('li'); li.textContent = t; tl.appendChild(li); });

    var others = D.records.filter(function (x) { return x.id !== r.id && x.ntitle === r.ntitle && x.kind !== 'course'; });
    var aw = $('dlg-also-wrap'), al = $('dlg-also'); al.textContent = '';
    aw.hidden = others.length === 0;
    others.forEach(function (x) {
      var li = document.createElement('li'); li.textContent = progById[x.prog].pageLabel + ' — ' + x.level + ' (' + x.method + ')'; al.appendChild(li);
    });

    var link = $('dlg-link'); link.href = p.page; link.textContent = 'اذهب إلى صفحة ' + p.pageLabel;
    if (typeof dlg.showModal === 'function') dlg.showModal(); else dlg.setAttribute('open', '');
  }
  grid.addEventListener('click', function (e) {
    var b = e.target.closest('.book'); if (b) openDetail(b.dataset.id);
  });
  $('dlg-close').addEventListener('click', function () { dlg.close(); });
  dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });

  try {
    var q0 = new URLSearchParams(location.search).get('q');
    if (q0) { input.value = q0; state.q = norm(q0); }
  } catch (e) {}
  render();
})();
