/* التسجيل والتواصل — inquiry form: opens WhatsApp with the message when a number is set (data-wa), otherwise shows a copy */
(function () {
  'use strict';

  var form = document.getElementById('inquiryForm');
  var done = document.getElementById('inquiryDone');
  var copy = document.getElementById('inquiryCopy');
  var reset = document.getElementById('inquiryReset');
  var faq = document.getElementById('faq');
  if (!form) return;

  var fields = [
    { id: 'fName', err: 'eName', msg: 'فضلًا اكتب اسمك.', ok: function (v) { return v.trim().length >= 2; } },
    { id: 'fLevel', err: 'eLevel', msg: 'فضلًا اختر المستوى الذي يهمك.', ok: function (v) { return v !== ''; } },
    { id: 'fMsg', err: 'eMsg', msg: 'فضلًا اكتب استفسارك (٥ أحرف على الأقل).', ok: function (v) { return v.trim().length >= 5; } }
  ];

  function check(f, silent) {
    var input = document.getElementById(f.id);
    var errEl = document.getElementById(f.err);
    var good = f.ok(input.value);
    input.closest('.field').classList.toggle('has-error', !good);
    input.setAttribute('aria-invalid', String(!good));
    errEl.textContent = good ? '' : f.msg;
    return good;
  }

  fields.forEach(function (f) {
    var input = document.getElementById(f.id);
    input.addEventListener('blur', function () { check(f); });
    input.addEventListener('input', function () {
      if (input.closest('.field').classList.contains('has-error')) check(f);
    });
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var firstBad = null;
    fields.forEach(function (f) {
      if (!check(f) && !firstBad) firstBad = document.getElementById(f.id);
    });
    if (firstBad) { firstBad.focus(); return; }

    var details =
      'الاسم: ' + document.getElementById('fName').value.trim() + '\n' +
      'المستوى: ' + document.getElementById('fLevel').value + '\n' +
      'الاستفسار: ' + document.getElementById('fMsg').value.trim();
    copy.textContent = details;

    var wa = form.getAttribute('data-wa');
    if (wa) {
      var text = 'السلام عليكم، لدي استفسار عن مجمع دار العلوم الأثرية.\n\n' + details;
      var url = 'https://wa.me/' + wa + '?text=' + encodeURIComponent(text);
      var btn = document.getElementById('inquiryWa');
      if (btn) btn.href = url;
      // opened from the click itself, so browsers allow it; the button below is the fallback
      window.open(url, '_blank', 'noopener');
    }
    form.hidden = true;
    done.hidden = false;
    done.focus();
  });

  reset.addEventListener('click', function () {
    form.reset();
    fields.forEach(function (f) {
      var input = document.getElementById(f.id);
      input.closest('.field').classList.remove('has-error');
      input.removeAttribute('aria-invalid');
      document.getElementById(f.err).textContent = '';
    });
    done.hidden = true;
    form.hidden = false;
    document.getElementById('fName').focus();
  });

  /* #faq deep link (e.g. from the footer): open the first answer and scroll */
  if (faq && location.hash === '#faq') {
    var first = faq.querySelector('details');
    if (first) first.open = true;
  }
})();
