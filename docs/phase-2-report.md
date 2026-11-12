# Phase 2 report — Publishing and public resources

Date: 2026-11-10 · Branch: `claude/zealous-fermat-skd1yu`

## What shipped

- Eleven migrations creating the content schema: `exams`, `pages`, `page_redirects`, `pdfs`, `notices`, `hero_media`, `newsline_items`, `books`, `book_editions`, `reviews`, `study_materials`, plus `role`/`mfa_secret`/`mfa_enabled` on `users`.
- Eloquent models with `draft` / `published` / `withdrawn` status enums and scoped `published()` queries. Money stays in integer paise.
- Filament 5 admin panel at `/admin` with the three PRD sidebar groups (Content · People & Exams · Commerce), Emerald primary colour on the "TA exam guide · admin" brand.
- Nine Filament resources: Pages, Hero / slider, Newsline ticker, Notices, PDFs, Study material, Exam names, Books, Reviews.
- Notice admin: status/type Selects, auto-slug from title, source attribution section, inline **Publish** and **Withdraw** actions, filter chips for status / type / exam.
- PDF admin: `public` vs `private` disk selector, PDF-only file upload (25 MB cap), 2 real disks declared in `config/filesystems.php` (`public` = `storage/app/public`, `private` = `storage/app/private`, non-serving).
- Review admin: moderation state badge, inline **Approve** / **Reject** actions, moderator stamp.
- Public controllers moved off the Phase 1 fixtures onto Eloquent: homepage, notices index + detail, book product page.
- `GET /sitemap.xml`: only published + indexable + canonical 200 URLs; drafts, withdrawn notices, checkout and buyer routes stay out.
- `GET /pdfs/{id}`: redirects to the public-disk URL. Private PDFs served from this route **refuse with 403** — a draft private PDF cannot be dereferenced via a public link.
- Homepage caches latest-notices for five minutes; the inline Publish/Withdraw actions bust the cache so the public site updates on the next request.
- Policies for Notice, Page, Pdf, Review, Book, Exam, StudyMaterial, HeroMedia, NewslineItem. Operations role is view-only on all content; private-PDF upload is admin+editor only; delete is admin-only.
- Demo seeder (`Phase2DemoSeeder`) with one admin / editor / operations user, three exams, two PDFs (one public, one private), two published notices + one draft, a published book with one edition and one approved + one pending review, a published hero item, a published newsline item, and a buyer-only study material.

## Files changed (headline list)

- `database/migrations/2026_11_10_120000_create_content_tables.php`, `2026_11_10_120100_add_role_and_mfa_to_users.php`.
- `app/Models/{User,Exam,Page,Notice,Pdf,HeroMedia,NewslineItem,Book,BookEdition,Review,StudyMaterial}.php`.
- `app/Policies/{Notice,Page,Pdf,Review,Book,Exam,StudyMaterial,HeroMedia,NewslineItem}Policy.php`.
- `app/Providers/Filament/AdminPanelProvider.php` — brand, Emerald primary, three nav groups.
- `app/Filament/Resources/**` — 9 generated resources, nav groups patched, Notice + Pdf + Review hand-edited.
- `app/Http/Controllers/{Home,Notice,Book,Sitemap,PdfDownload}Controller.php`.
- `routes/web.php` — sitemap + public PDF route.
- `config/filesystems.php` — explicit `private` disk.
- `database/seeders/Phase2DemoSeeder.php`.
- `docs/screenshots/admin-*.png`.

## Acceptance tests — Phase 2 PRD prompt

> Demonstrate that publishing a synthetic notice updates the homepage and detail page; withdrawal clears both and the sitemap; a draft and a private file are inaccessible to an anonymous visitor.

Captured verbatim from a live run against `http://127.0.0.1:8010`:

```
--- Before withdrawal ---
home has the notice: 1
listing has the notice: 1
detail: 200
sitemap has the notice: 1
withdrawn
--- After withdrawal ---
home has the notice: 0
listing has the notice: 0
detail: 404
sitemap has the notice: 0
restored
```

Draft notice `/notices/draft-only-visible-in-admin` returns 404 to anonymous visitors and does not appear in the public listing. Private PDF via the public `GET /pdfs/{id}` route refuses with **403**.

## Guardrails honoured

- Three excluded modules (Exam Centres, Coupons, Upload Consignment CSV) are **not** scaffolded, migrated or routed.
- Operations role has no publish/create permission on content; no access to private-PDF uploads.
- Reviews are hidden on the public book page until moderator-approved. The seeded pending review does not render.
- Private files sit on a non-serving disk and are not reachable through the public PDF route.
- Money stored in integer paise; the Rs 1,998 price round-trips via `intdiv` without float arithmetic.

## Blocked / parked for later phases

- Cloudflare R2 behind the `public` / `private` disk names — the config is disk-driver-swappable, so Phase 4/5 only needs `AWS_*` env vars and the s3 driver.
- Transactional mail transport (SES vs Resend) still unselected.
- Preview-for-staff-only URL on drafts: Phase 2 shows drafts in Filament; the signed preview URL for sharing with a reviewer lands in Phase 3 if asked for.
- Full RelationManagers on Book → Editions / Reviews (scaffolded resources only; relation UX polish deferred).
- MFA challenge screen (Phase 6 per PRD definition of done).

## Screenshots

- `docs/screenshots/admin-login-1440.png`
- `docs/screenshots/admin-dashboard-1440.png`
- `docs/screenshots/admin-notices-list-1440.png`
- `docs/screenshots/admin-notice-edit-1440.png`
- `docs/screenshots/admin-pdfs-list-1440.png`
- `docs/screenshots/admin-reviews-1440.png`
- `docs/screenshots/admin-books-list-1440.png`

## Next

Phase 3 — Officer + Soldier subscribers, Result Registration, email verification + unsubscribe + suppression gate, filtered Excel export, newsletter preview/test. Prove deduplication, audience-specific unsubscribe, and the removed-features assertion.
