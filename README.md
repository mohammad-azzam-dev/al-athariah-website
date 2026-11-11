# مجمع دار العلوم الأثرية — موقع الكلية والمدرسة والمكتبة

Laravel 12 (Blade) · plain CSS in `public/css` · vanilla JS in `public/js` · no Node build step.
Content source: «الدليل الشامل — قسم التعليم عن بُعد ١٤٤٨هـ».

```bash
composer install && cp .env.example .env && php artisan key:generate
php artisan migrate   # sqlite
php artisan serve
```

Pages: `/` `/college` `/school` `/maqra` (القرآن، السنة، المتون العلمية) `/library` `/path` `/register` — `/matun` redirects to `/maqra#matun`
Content arrays: `app/Data/*Data.php` · Views: `resources/views/pages` · Animation engine: `public/js/app.js` + `public/css/animations.css`.
