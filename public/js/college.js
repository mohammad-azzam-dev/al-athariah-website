/* الكلية الأثرية — subject quick-filter + level-card shortcuts */
(function () {
  'use strict';
  var input = document.getElementById('subject-filter');
  var status = document.getElementById('filter-status');

  function norm(s) {
    return (s || '').replace(/[ً-ٰٟـ]/g, '')
      .replace(/[أإآ]/g, 'ا').replace(/ى/g, 'ي').replace(/ة/g, 'ه').toLowerCase().trim();
  }

  var rows = Array.prototype.slice.call(document.querySelectorAll('.college-tools ~ [role="tabpanel"] tbody tr'));
  rows.forEach(function (r) { r._t = norm(r.textContent); });

  function apply() {
    var q = norm(input.value);
    rows.forEach(function (r) {
      r.hidden = !!q && r._t.indexOf(q) === -1;
      r.classList.remove('is-match');
      if (q && !r.hidden) { void r.offsetWidth; r.classList.add('is-match'); }
    });
    if (!q) { status.textContent = ''; return; }
    var shown = rows.filter(function (r) { return !r.hidden && r.offsetParent !== null; }).length;
    status.textContent = shown ? 'عدد النتائج الظاهرة: ' + shown : 'لا توجد نتائج مطابقة في هذا القسم';
  }
  if (input) {
    input.addEventListener('input', apply);
    document.addEventListener('click', function (e) {
      if (e.target.closest('[role="tab"]')) setTimeout(apply, 0);
    });
  }

  document.querySelectorAll('[data-goto]').forEach(function (a) {
    a.addEventListener('click', function () {
      var tab = document.querySelector('[role="tab"][aria-controls="' + a.getAttribute('data-goto') + '"]');
      if (tab) tab.click();
    });
  });
})();
