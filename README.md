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

## Admin dashboard (AdminLTE 4 + Fortify)

`/admin` — library management for admins: add, edit, hide and delete books, and upload their digital copies.

- UI: AdminLTE 4 RTL + Bootstrap 5.3 + Bootstrap Icons, vendored in `public/vendor/` (no build step). Theme overrides: `public/css/admin.css`.
- Auth: Laravel Fortify (login, logout, forgot/reset password, profile and password update). Registration is off; only users with `is_admin = 1` can sign in.
- Create or promote an admin:

  ```bash
  php artisan athariyah:admin you@example.com --name="الاسم" --password="a-strong-password"
  ```

- Books live in the `books` table. The migration seeds it with the full catalogue from `LibraryData`, so `/library` reads from the database (falling back to the static list if the table is missing or empty).
- Password-reset emails need `MAIL_*` configured in `.env`.

### Cloudflare R2 storage for book files

Files are stored privately on the `r2` disk (S3-compatible). Visitors get a short-lived signed link from `/library/books/{id}/file`.

1. In Cloudflare: **R2 → Create bucket** (keep it private).
2. **R2 → Manage API tokens → Create token** with *Object Read & Write* on that bucket.
3. Add to `.env` (never commit these):

   ```env
   BOOKS_DISK=r2
   R2_ACCESS_KEY_ID=...
   R2_SECRET_ACCESS_KEY=...
   R2_BUCKET=athariah-library
   R2_ENDPOINT=https://<ACCOUNT_ID>.r2.cloudflarestorage.com
   ```

4. `php artisan config:clear` (or `config:cache` in production).

For local development without R2, set `BOOKS_DISK=local`. Limits (types, size, link lifetime) are in `config/athariyah.php` → `books`.
