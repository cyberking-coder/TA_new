# Phase 1 report — Foundation and visual prototype

Date: 2026-11-10 · Branch: `claude/zealous-fermat-skd1yu`

## What shipped

- Laravel 13.40 scaffold, PHP 8.3 (inside the PRD 8.3–8.5 range).
- Tailwind 4 + Vite 8 + Alpine 3 wired via the Laravel Vite plugin.
- SQLite for local dev; MySQL 8.4 remains the staging/production target.
- Brand theme (forest green `#173D32` on ivory `#FAF8F2`, charcoal body, ochre accent) applied in `resources/css/app.css` with explicit focus-visible styles, reduced-motion handling and a `.synthetic-banner` so no screenshot can be mistaken for production.
- Responsive public shell: header + footer partials, skip-link, keyboard-focus ring.
- Routes: homepage, notices index, notice detail, book product page, placeholder pages for routes that land in later phases, `robots.txt` (disallow-all off production), and the P0 legacy redirects from the PRD (`/home`, `/book/5`, `/imp_downloads`, `/pib_question_papers`, `/practice_test_papers`, `/login`, `/buy_now/5`).
- Synthetic fixtures in `app/Support/SyntheticFixtures.php`. Every record is clearly labelled `[Synthetic]`. No real person, publisher or legacy record is present.

## Files changed (headline list)

- `composer.json`, `composer.lock`, `package.json`, `package-lock.json` — dep lockfiles committed (PRD: "Resolve and commit Composer and npm lockfiles").
- `vite.config.js` — remote-font auto-resolution disabled in this container (outbound DNS for `fonts.bunny.net` denied by proxy); Barlow + Source Sans 3 will be self-hosted before launch per PRD.
- `resources/css/app.css`, `resources/js/app.js` — brand theme + Alpine bootstrap.
- `resources/views/layouts/public.blade.php`, `partials/header.blade.php`, `partials/footer.blade.php`.
- `resources/views/home/index.blade.php`, `notices/index.blade.php`, `notices/show.blade.php`, `books/show.blade.php`, `static/placeholder.blade.php`.
- `app/Http/Controllers/HomeController.php`, `NoticeController.php`, `BookController.php`, `PlaceholderController.php`.
- `app/Support/SyntheticFixtures.php`.
- `routes/web.php` — real public routes + legacy redirects.
- `docs/` — build-status, route-inventory, schema-outline, permissions-matrix, seo-baseline, phase-1-report, screenshots.

## Exact commands run

```bash
# scaffold
COMPOSER_ALLOW_SUPERUSER=1 composer create-project laravel/laravel tmp_laravel --no-interaction --prefer-dist
mv tmp_laravel/* tmp_laravel/.??* .  # relocated to repo root

# local db
touch database/database.sqlite
# .env edited: DB_CONNECTION=sqlite; MySQL env vars removed

# npm
npm install alpinejs
npm install
npm run build

# serve + smoke
php artisan serve --host=127.0.0.1 --port=8010 &
curl -o /dev/null -w '%{http_code}' http://127.0.0.1:8010/  # 200
curl -o /dev/null -w '%{http_code}' http://127.0.0.1:8010/home  # 301
# ...and the rest (see "Smoke test" below)

# screenshots + keyboard trail
node scratchpad/screenshots.cjs
```

## Smoke test — HTTP response codes

| Route | Expect | Got |
|---|---|---|
| `GET /` | 200 | 200 |
| `GET /notices-and-downloads` | 200 | 200 |
| `GET /notices/tac-2026-notification` | 200 | 200 |
| `GET /books/tac-examination-book` | 200 | 200 |
| `GET /alerts/officer` | 200 (placeholder) | 200 |
| `GET /study/login` | 200 (placeholder, `noindex`) | 200 |
| `GET /robots.txt` | 200 | 200 |
| `GET /home` | 301 → `/` | 301 |
| `GET /book/5` | 301 → `/books/tac-examination-book` | 301 |
| `GET /imp_downloads` | 301 → `/notices-and-downloads` | 301 |
| `GET /pib_question_papers` | 301 → `/preparation/previous-papers` | 301 |
| `GET /practice_test_papers` | 301 → `/preparation/practice-papers` | 301 |
| `GET /login` | 301 → `/study/login` | 301 |
| `GET /buy_now/5` | 301 → `/checkout/tac-examination-book` | 301 |
| `GET /notices/no-such-notice` | 404 | 404 |

## SEO baseline — rebuild templates

The legacy baseline (every sampled page: empty title, empty description, no H1, no canonical, no JSON-LD, 47 images missing alt) is captured in `docs/seo-baseline.md`. After Phase 1 the rebuild templates now meet the P0 bar:

| Route | Title | Description | H1 | Canonical | JSON-LD |
|---|---|---|---|---|---|
| `/` | "Territorial Army exam guide — notices, preparation and the book" | present | one, descriptive | present | WebSite |
| `/notices-and-downloads` | "Notices and downloads — Territorial Army exam guide" | present | one | present | BreadcrumbList |
| `/notices/tac-2026-notification` | "[Synthetic] TAC 2026 written notification — Territorial Army exam guide" | present | one | present | NewsArticle |
| `/books/tac-examination-book` | "Territorial Army Commission Examination Book — Edition 2026 — Territorial Army exam guide" | present | one | present | Product + Offer (visible INR price) |

Placeholder routes for later phases (`/alerts/*`, `/study/login`, `/checkout/*`, `/policies/*`) either carry a descriptive title or a `noindex` directive where they should not land in search.

## Accessibility & keyboard evidence

- Skip-to-content link renders visibly on focus (first tab stop).
- Visible focus ring via `:focus-visible` outline: 3px ochre, offset 2px, radius 2px.
- Reduced-motion media query strips transitions/animations.
- Primary interactive targets (`.btn-primary`, `.btn-ghost`, `.tile`) have `min-height: 44px` or comparable bounds, consistent with the PRD's 44 × 44 CSS px target guidance.
- Tab order captured in `docs/screenshots/keyboard-focus-trail.txt`:
  ```
  tab1: a:Skip to main content
  tab2: a:Territorial Army exam guide
  tab3: a:Notices
  tab4: a:Prepare
  tab5: a:Books
  tab6: a:Study login
  tab7: a:View latest notices
  tab8: a:About the book
  ```

Axe automated runs land in Phase 5 per PRD.

## Mobile layout check

PRD: "On mobile put headline and action before the image." Confirmed at 390 px: badge → H1 → paragraph → *View latest notices* → *About the book* → hero placeholder → four task tiles.

## Guardrails honoured

- No production access, no customer data, no legacy database reached.
- Every synthetic record is clearly labelled.
- Fonts.bunny.net blocked by proxy — remote font auto-resolution removed from Vite; PRD-mandated self-hosted Barlow and Source Sans 3 land in Phase 2 when the asset drop arrives. Container's system sans stack renders cleanly in the meantime.
- Payment, mail, R2 and CCAvenue are **not** wired. Phase 4 lands the provider adapter + local simulator; merchant sandbox evidence stays separate.

## Unresolved / blocked

Carried into `docs/build-status.md`:

- Licensed hero photo, real book cover, four task pictograms, licence records.
- Self-hosted Barlow & Source Sans 3 font files (blocked by proxy in container; client supplies for launch).
- CCAvenue merchant test credentials + integration kit.
- Transactional-mail transport (SES vs Resend) decision.
- Cloudflare R2 credentials / buckets.
- Authorised offline legacy data extract.
- Quantity-pricing rounding rule ratification and manual-tracking decision.

## Next

Phase 2 — publishing & public resources. Eloquent migrations for `pages`, `notices`, `pdfs`, `study_materials`, `reviews`, `hero_media`, `newsline_items`; Filament 5 panel skeleton with the three sidebar groups; draft/preview/publish/withdraw; review moderation; distinct public vs private file disks.
