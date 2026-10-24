/* أثر — shared behaviour + animation engine (vanilla ES6, no dependencies). */
(function () {
  'use strict';
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var AR = '٠١٢٣٤٥٦٧٨٩';
  var toAr = function (n) { return String(n).replace(/\d/g, function (d) { return AR[d]; }); };

  /* ---------- Page intro (once per session) ---------- */
  (function () {
    var intro = document.querySelector('.page-intro');
    if (!intro || reduce) return;
    var seen = false;
    try { seen = sessionStorage.getItem('athariyah-intro') === '1'; sessionStorage.setItem('athariyah-intro', '1'); } catch (e) {}
    if (seen || document.body.getAttribute('data-page') !== 'home') return;
    document.documentElement.classList.add('show-intro');
    setTimeout(function () { intro.classList.add('is-done'); }, 1300);
  })();

  /* ---------- Drawer ---------- */
  (function () {
    var drawer = document.getElementById('drawer');
    var btn = document.querySelector('.menu-btn');
    if (!drawer || !btn) return;
    function set(open) {
      drawer.classList.toggle('is-open', open);
      drawer.setAttribute('aria-hidden', String(!open));
      btn.setAttribute('aria-expanded', String(open));
      document.body.style.overflow = open ? 'hidden' : '';
      if (open) drawer.querySelector('.drawer__close').focus(); else btn.focus();
    }
    btn.addEventListener('click', function () { set(true); });
    drawer.addEventListener('click', function (e) { if (e.target.closest('[data-close]') || e.target.closest('a')) set(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && drawer.classList.contains('is-open')) set(false); });
  })();

  /* ---------- Scroll: header, progress, to-top, parallax ---------- */
  (function () {
    var header = document.querySelector('.site-header');
    var top = document.querySelector('.to-top');
    var bar = document.querySelector('.scroll-progress');
    var para = $$('[data-parallax]');
    var last = window.scrollY, ticking = false;
    function frame() {
      var y = window.scrollY, h = document.documentElement.scrollHeight - window.innerHeight;
      if (header) {
        header.classList.toggle('is-scrolled', y > 8);
        header.classList.toggle('is-hidden', y > last && y > 400 && !document.body.style.overflow);
      }
      if (top) top.classList.toggle('is-visible', y > 600);
      if (bar && !reduce) bar.style.transform = 'scaleX(' + (h > 0 ? y / h : 0) + ')';
      if (!reduce) para.forEach(function (el) { el.style.transform = 'translate3d(0,' + (y * parseFloat(el.getAttribute('data-parallax'))).toFixed(1) + 'px,0)'; });
      last = y; ticking = false;
    }
    window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(frame); } }, { passive: true });
    frame();
    if (top) top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' }); });
  })();

  /* ---------- Tabs: <div role="tablist" data-tabs> <button role="tab" aria-controls> … panels role="tabpanel" ---------- */
  $$('[data-tabs]').forEach(function (list) {
    var tabs = $$('[role="tab"]', list);
    function select(tab) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.setAttribute('aria-selected', String(on)); t.tabIndex = on ? 0 : -1;
        var p = document.getElementById(t.getAttribute('aria-controls'));
        if (p) { p.hidden = !on; if (on) $$('[data-anim]', p).forEach(function (el) { el.classList.remove('is-visible'); void el.offsetWidth; observe(el); }); }
      });
    }
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { select(t); });
      t.addEventListener('keydown', function (e) {
        var n = null;
        if (e.key === 'ArrowLeft') n = tabs[(i + 1) % tabs.length];
        if (e.key === 'ArrowRight') n = tabs[(i - 1 + tabs.length) % tabs.length];
        if (n) { e.preventDefault(); select(n); n.focus(); }
      });
    });
    var init = tabs.filter(function (t) { return t.getAttribute('aria-selected') === 'true'; })[0] || tabs[0];
    if (init) select(init);
  });

  /* ---------- Split heading words ---------- */
  $$('[data-split]').forEach(function (el) {
    var words = el.textContent.trim().split(/\s+/), accent = el.querySelector('[class*="accent"]');
    if (accent) return;                       // keep markup headings intact; they use data-anim instead
    el.setAttribute('aria-label', el.textContent.trim());
    el.innerHTML = words.map(function (w, i) {
      return '<span class="split-word" aria-hidden="true"><span style="--d:' + (i * 90) + 'ms">' + w + '</span></span>';
    }).join(' ');
    if (reduce) return; setTimeout(function () { el.classList.add('is-visible'); }, 120);
  });

  /* ---------- Reveal / counters / draw ---------- */
  var io = null;
  function countUp(el) {
    var to = parseFloat(el.getAttribute('data-count')), dur = 1600, t0 = null;
    var sep = el.hasAttribute('data-sep');
    if (reduce) { el.textContent = fmt(to); return; }
    function fmt(n) { var s = Math.round(n).toString(); if (sep) s = s.replace(/\B(?=(\d{3})+(?!\d))/g, '٬'); return toAr(s); }
    function step(ts) {
      if (!t0) t0 = ts;
      var p = Math.min((ts - t0) / dur, 1), e = 1 - Math.pow(1 - p, 4);
      el.textContent = fmt(to * e);
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  function show(el) {
    el.classList.add('is-visible');
    if (el.hasAttribute('data-count')) countUp(el);
  }
  function observe(el) { if (io) io.observe(el); else show(el); }
  (function () {
    $$('[data-stagger]').forEach(function (p) {
      var step = parseInt(p.getAttribute('data-stagger'), 10) || 90;
      $$(':scope > *', p).forEach(function (c, i) {
        if (!c.hasAttribute('data-anim') && !c.classList.contains('reveal')) c.setAttribute('data-anim', 'fade-up');
        c.style.setProperty('--d', (i * step) + 'ms');
      });
    });
    $$('[data-delay]').forEach(function (el) { el.style.setProperty('--d', el.getAttribute('data-delay') + 'ms'); });
    $$('.draw').forEach(function (p) { try { p.style.setProperty('--len', Math.ceil(p.getTotalLength())); } catch (e) {} });
    var targets = $$('[data-anim], .reveal, [data-count], .draw');
    if (!('IntersectionObserver' in window) || reduce) { targets.forEach(show); return; }
    io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { show(en.target); io.unobserve(en.target); } });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
    targets.forEach(function (el) { io.observe(el); });
  })();

  /* ---------- 3D tilt ---------- */
  if (!reduce && window.matchMedia('(hover: hover)').matches) {
    $$('[data-tilt]').forEach(function (el) {
      el.addEventListener('mousemove', function (e) {
        var r = el.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
        el.style.transform = 'perspective(900px) rotateY(' + (x * 8).toFixed(2) + 'deg) rotateX(' + (-y * 8).toFixed(2) + 'deg) translateY(-4px)';
      });
      el.addEventListener('mouseleave', function () { el.style.transform = ''; });
    });
  }

  /* ---------- Page transition on internal links ---------- */
  if (!reduce) {
    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[href]');
      if (!a || e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || a.target === '_blank' || a.hasAttribute('download')) return;
      var u = new URL(a.href, location.href);
      if (u.origin !== location.origin || (u.pathname === location.pathname && u.search === location.search)) return;
      e.preventDefault(); document.body.classList.add('is-leaving');
      setTimeout(function () { location.href = a.href; }, 200);
    });
    window.addEventListener('pageshow', function () { document.body.classList.remove('is-leaving'); });
  }

  window.Athariyah = { toAr: toAr, reveal: observe, reduce: reduce };
})();
