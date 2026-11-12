# Release notes — Territorial Army exam guide rebuild

Release candidate · Branch `claude/zealous-fermat-skd1yu` · 2026-11-10

## What this is

A clean Laravel 13 + Filament 5 rebuild of jointerritorialarmy.com, delivered against the PRD at `Territorial_Army_Claude_Code_PRD.md` and the field review at `Territorial_Army_Rebuild_Plan.pdf`. Six phases, built in order, each with its own report under `docs/phase-N-report.md`.

## What works

- Public site: homepage with hero + four task tiles + dated notices + book feature; filterable notices listing; notice detail with source attribution; book product page with INR price + moderated reviews.
- Single-column mobile checkout, server-priced totals, no coupon field.
- CCAvenue-shaped payment flow with a deterministic local simulator for CI and staging.
- Buyer login with order-ref + password, signed short-lived downloads of private PDFs, entitlement re-checked per request.
- Officer / soldier alert signups with email verification, audience-scoped unsubscribe, suppression re-checked at send time.
- Result registration (name, mobile, email, exam, roll) with `(exam, roll)` dedupe.
- Filament 5 admin at `/admin` with 11 resources in three groups (Content · People & Exams · Commerce), role-gated policies, MFA-ready.
- Idempotent legacy importers with `--dry-run`, two-run rehearsal harness, SEO audit CSV, redirect map CSV.
- Backup + restore commands + a round-trip rehearsal script.
- 31 PHPUnit tests, 750+ assertions, green.

## What needs owner input before launch

Enumerated in full in `docs/phase-6-report.md`. Headline items:

1. CCAvenue merchant kit — required for the real sandbox check.
2. Transactional mail transport — SES or Resend.
3. Cloudflare R2 bucket + credentials.
4. Hostinger VPS + SSH access.
5. Licensed hero photo, book cover artwork, pictograms.
6. Authorised offline legacy data extract.

Nothing in this release contacts production systems or scrapes customer data.

## How to run locally

```bash
git clone https://github.com/cyberking-coder/TA_new.git
cd TA_new
git checkout claude/zealous-fermat-skd1yu
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
# set DB_CONNECTION=sqlite in .env, remove the mysql vars
php artisan migrate --seed --seeder=Phase2DemoSeeder
php artisan serve
```

Login: `admin@example.test` / `password` at `/admin/login`. Two other seeded roles: `editor@example.test`, `ops@example.test`.

## How to deploy

See `deploy/README.md` and `docs/handover/01-release-checklist.md`. The templates target Hostinger VPS + Nginx + PHP-FPM 8.4 + Supervisor + systemd.

## Scope boundaries honoured

- No exam centres, coupons, upload-consignment-CSV, or "Move to 100% Officers" — asserted by `tests/Feature/Phase3ExclusionsTest.php`.
- No plaintext passwords anywhere.
- No production access during the build.
- Merchant sandbox verification is explicitly PENDING in writing (`docs/phase-4-report.md`).

## Handover index

- `docs/phase-1-report.md` through `docs/phase-6-report.md`
- `docs/build-status.md` — single-page status + outstanding owner inputs
- `docs/route-inventory.md` · `docs/schema-outline.md` · `docs/permissions-matrix.md` · `docs/seo-baseline.md`
- `docs/redirect-map.csv` · `docs/seo-audit.csv`
- `docs/handover/01-release-checklist.md` · `02-environment-requirements.md` · `03-staff-walkthrough.md` · `04-tracking-sheet.csv` · `05-rollback-runbook.md`
- `deploy/nginx/ta.conf` · `deploy/supervisor/ta-worker.conf` · `deploy/systemd/ta-scheduler.*` · `deploy/scripts/*.sh`
