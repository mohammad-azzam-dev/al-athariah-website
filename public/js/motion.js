/* أثر — motion layer: floating dust, spotlight, magnetic buttons, ripples, menu ink,
   reading-progress ring, icon re-draw. Styles in public/css/motion.css.
   Nothing here runs for prefers-reduced-motion; pointer effects only run with a mouse. */
(function () {
  'use strict';
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;
  var fine = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* ---------- 1. Gold dust floating behind the green heroes ---------- */
  $$('.hero, .page-hero').forEach(function (host) {
    var cv = document.createElement('canvas');
    cv.className = 'dust'; cv.setAttribute('aria-hidden', 'true');
    host.appendChild(cv);
    var ctx = cv.getContext('2d'), dpr = Math.min(window.devicePixelRatio || 1, 2), w = 0, h = 0, parts = [], running = false, raf = 0;
    function size() {
      w = host.clientWidth; h = host.clientHeight;
      cv.width = Math.round(w * dpr); cv.height = Math.round(h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      var n = Math.round(Math.min(70, (w * h) / 16000));
      parts = [];
      for (var i = 0; i < n; i++) parts.push(seed(true));
    }
    function seed(anywhere) {
      return {
        x: Math.random() * w, y: anywhere ? Math.random() * h : h + 10,
        r: .6 + Math.random() * 1.8, vy: .12 + Math.random() * .35, vx: (Math.random() - .5) * .15,
        tw: Math.random() * Math.PI * 2, ts: .01 + Math.random() * .025, hue: Math.random() < .8 ? 'gold' : 'teal'
      };
    }
    function frame() {
      ctx.clearRect(0, 0, w, h);
      for (var i = 0; i < parts.length; i++) {
        var p = parts[i];
        p.y -= p.vy; p.x += p.vx + Math.sin(p.tw) * .12; p.tw += p.ts;
        if (p.y < -10) parts[i] = p = seed(false);
        var a = .25 + .55 * (0.5 + 0.5 * Math.sin(p.tw));
        var g = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r * 4);
        var c = p.hue === 'gold' ? '232, 210, 154' : '99, 210, 199';
        g.addColorStop(0, 'rgba(' + c + ',' + a + ')'); g.addColorStop(1, 'rgba(' + c + ',0)');
        ctx.fillStyle = g; ctx.beginPath(); ctx.arc(p.x, p.y, p.r * 4, 0, Math.PI * 2); ctx.fill();
      }
      raf = requestAnimationFrame(frame);
    }
    function start() { if (!running) { running = true; raf = requestAnimationFrame(frame); } }
    function stop() { running = false; cancelAnimationFrame(raf); }
    size();
    window.addEventListener('resize', function () { size(); });
    // only animate while the hero is on screen and the tab is visible
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (en) { en[0].isIntersecting && !document.hidden ? start() : stop(); }).observe(host);
    } else start();
    document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
  });

  /* ---------- 4. Card icons re-draw on hover: give each stroke its length ---------- */
  $$('.house__arch svg path, .house__arch svg rect, .house__arch svg line').forEach(function (el) {
    try { var len = Math.ceil(el.getTotalLength()); el.setAttribute('data-len', ''); el.style.setProperty('--len', len); } catch (e) {}
  });

  /* ---------- 9. Reading-progress ring on the back-to-top button ---------- */
  (function () {
    var btn = document.querySelector('.to-top');
    if (!btn) return;
    var R = 26, C = 2 * Math.PI * R;
    btn.insertAdjacentHTML('beforeend',
      '<svg class="to-top__ring" viewBox="0 0 58 58" aria-hidden="true"><circle class="track" cx="29" cy="29" r="' + R + '"/>' +
      '<circle class="bar" cx="29" cy="29" r="' + R + '" stroke-dasharray="' + C.toFixed(1) + '" stroke-dashoffset="' + C.toFixed(1) + '"/></svg>');
    var bar = btn.querySelector('.bar'), ticking = false;
    function update() {
      var max = document.documentElement.scrollHeight - window.innerHeight;
      var p = max > 0 ? Math.min(1, window.scrollY / max) : 0;
      bar.setAttribute('stroke-dashoffset', (C * (1 - p)).toFixed(1));
      ticking = false;
    }
    window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
    update();
  })();

  /* ---------- 6. Ink ripple where buttons, chips, tabs and options are pressed ---------- */
  document.addEventListener('pointerdown', function (e) {
    var host = e.target.closest('.btn, .chip, .tabs [role="tab"], .finder__opt, .nav__link, .theme-btn');
    if (!host || e.button > 0) return;
    host.classList.add('ripple-host');
    var r = host.getBoundingClientRect(), d = Math.max(r.width, r.height) * 2.2;
    var s = document.createElement('span');
    s.className = 'ripple';
    s.style.width = s.style.height = d + 'px';
    s.style.left = (e.clientX - r.left - d / 2) + 'px';
    s.style.top = (e.clientY - r.top - d / 2) + 'px';
    host.appendChild(s);
    setTimeout(function () { s.remove(); }, 700);
  });

  if (!fine) return; // the rest follows the mouse

  /* ---------- 3. Cursor spotlight on cards ---------- */
  $$('.card:not(.book):not(.reg-cta__card), .finder__opt').forEach(function (el) {
    el.classList.add('spot');
    el.addEventListener('pointermove', function (e) {
      var r = el.getBoundingClientRect();
      el.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      el.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });

  /* ---------- 5. Magnetic primary buttons ---------- */
  $$('.btn--primary, .btn--lg').forEach(function (el) {
    if (el.closest('.nav')) return;
    el.classList.add('magnetic');
    el.addEventListener('pointermove', function (e) {
      var r = el.getBoundingClientRect();
      var x = (e.clientX - r.left - r.width / 2) / r.width, y = (e.clientY - r.top - r.height / 2) / r.height;
      el.style.transform = 'translate(' + (x * 10).toFixed(1) + 'px,' + (y * 8 - 2).toFixed(1) + 'px)';
    });
    el.addEventListener('pointerleave', function () { el.style.transform = ''; });
  });

  /* ---------- 7. Gold line that slides under the menu ---------- */
  (function () {
    var list = document.querySelector('.nav__list');
    if (!list) return;
    var ink = document.createElement('span');
    ink.className = 'nav__ink'; ink.setAttribute('aria-hidden', 'true');
    list.appendChild(ink);
    var links = $$('.nav__link', list);
    function to(a) {
      if (!a) { ink.style.opacity = '0'; return; }
      var lr = list.getBoundingClientRect(), r = a.getBoundingClientRect();
      ink.style.left = (r.left - lr.left + 14) + 'px';
      ink.style.width = Math.max(0, r.width - 28) + 'px';
      ink.style.opacity = '1';
    }
    var current = list.querySelector('.nav__link[aria-current="page"]');
    links.forEach(function (a) {
      a.addEventListener('mouseenter', function () { to(a); });
      a.addEventListener('focus', function () { to(a); });
    });
    list.addEventListener('mouseleave', function () { to(current); });
    window.addEventListener('resize', function () { to(current); });
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(function () { to(current); });
    to(current);
  })();
})();
