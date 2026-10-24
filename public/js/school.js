/* المدرسة الأثرية — filters, search and view toggle over the server-rendered subjects. */
(function () {
  'use strict';

  var cardsEl = document.getElementById('subject-cards');
  var tableBox = document.getElementById('subject-table');
  var chipsEl = document.getElementById('field-chips');
  var countEl = document.getElementById('result-count');
  var emptyEl = document.getElementById('empty-msg');
  var searchEl = document.getElementById('subject-search');
  var viewEl = document.querySelector('.school-tools__view');
  if (!cardsEl || !tableBox || !chipsEl || !searchEl) return;

  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var state = { field: 'الكل', q: '' };

  function norm(s) {
    return String(s).replace(/[ً-ٟـٰ]/g, '').replace(/[أإآ]/g, 'ا').replace(/ى/g, 'ي').replace(/ة/g, 'ه').toLowerCase();
  }

  var cards = Array.prototype.slice.call(cardsEl.querySelectorAll('.school-card'));
  var bodies = {};
  Array.prototype.forEach.call(tableBox.querySelectorAll('tbody[data-id]'), function (b) { bodies[b.getAttribute('data-id')] = b; });
  var items = cards.map(function (card) {
    var id = card.getAttribute('data-id');
    return { card: card, body: bodies[id], field: card.getAttribute('data-field'), hay: norm(card.textContent.replace(/\s+/g, ' ')) };
  });

  function pop(el) {
    if (reduce) return;
    el.classList.remove('is-pop');
    void el.offsetWidth;
    el.classList.add('is-pop');
    el.addEventListener('animationend', function h() { el.classList.remove('is-pop'); el.removeEventListener('animationend', h); });
  }

  function apply() {
    var q = norm(state.q.trim());
    var shown = 0;
    items.forEach(function (it) {
      var ok = (state.field === 'الكل' || it.field === state.field) && (!q || it.hay.indexOf(q) !== -1);
      var was = !it.card.hidden;
      it.card.hidden = !ok;
      if (it.body) it.body.hidden = !ok;
      if (ok) { shown++; if (!was) pop(it.card); }
    });
    countEl.textContent = 'عدد المواد المعروضة: ' + shown.toLocaleString('ar-EG');
    emptyEl.hidden = shown !== 0;
  }

  chipsEl.addEventListener('click', function (e) {
    var b = e.target.closest('[data-field]');
    if (!b) return;
    state.field = b.getAttribute('data-field');
    Array.prototype.forEach.call(chipsEl.children, function (c) { c.setAttribute('aria-pressed', String(c === b)); });
    apply();
  });

  searchEl.addEventListener('input', function () { state.q = searchEl.value; apply(); });

  viewEl.addEventListener('click', function (e) {
    var b = e.target.closest('[data-view]');
    if (!b) return;
    var table = b.getAttribute('data-view') === 'table';
    cardsEl.hidden = table;
    tableBox.hidden = !table;
    pop(table ? tableBox : cardsEl);
    Array.prototype.forEach.call(b.parentNode.children, function (c) { c.setAttribute('aria-pressed', String(c === b)); });
  });

  apply();
})();
