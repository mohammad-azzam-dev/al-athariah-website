/* المتون — طيّ/فتح قوائم المتون (الأزرار مخفية حتى يعمل JS؛ المحتوى مفتوح دائمًا بدونه) */
(function () {
  'use strict';
  var btns = Array.prototype.slice.call(document.querySelectorAll('[data-toggle]'));
  var all = document.getElementById('toggle-all');

  function set(b, open) {
    var body = document.getElementById(b.getAttribute('aria-controls'));
    if (!body) return;
    body.classList.toggle('is-collapsed', !open);
    if ('inert' in body) body.inert = !open;
    b.setAttribute('aria-expanded', String(open));
    b.textContent = (open ? 'إخفاء المتون' : 'عرض المتون') + ' (' + b.getAttribute('data-count-label') + ')';
  }
  function sync() {
    if (!all) return;
    var open = btns.some(function (b) { return b.getAttribute('aria-expanded') === 'true'; });
    all.textContent = open ? 'طيّ الكل' : 'فتح الكل';
    all.setAttribute('data-state', open ? 'open' : 'closed');
  }
  btns.forEach(function (b) {
    b.hidden = false;
    b.addEventListener('click', function () { set(b, b.getAttribute('aria-expanded') !== 'true'); sync(); });
  });
  if (all) {
    all.hidden = false;
    all.addEventListener('click', function () {
      var open = all.getAttribute('data-state') !== 'open';
      btns.forEach(function (b) { set(b, open); });
      sync();
    });
  }
})();
