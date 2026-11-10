# Build status

Living log for the Territorial Army rebuild. Update at the end of each working session so the next session can resume without re-reading the PRD end-to-end.

## Phase progress

| Phase | Status | Started | Finished | Notes |
|---|---|---|---|---|
| 1 Foundation & visual prototype | done | 2026-11-10 | 2026-11-10 | Scaffold + homepage + notice detail + book template, synthetic fixtures. |
| 2 Publishing & public resources | done | 2026-11-10 | 2026-11-10 | Schema + Filament 5 admin w/ three groups + 9 resources; draft/withdraw/publish wired to public HTML + sitemap; public vs private PDF disks; review moderation. |
| 3 Alerts & result registration | done | 2026-11-10 | 2026-11-10 | Officer + soldier subscribers, result registration, verification + unsubscribe + suppression gate, Excel/CSV export (admin+ops), newsletter preview/test/send with send-time suppression re-check. 11 tests, 592 assertions green. |
| 4 Checkout, payment, buyer access | done | 2026-11-10 | 2026-11-10 | Orders + items + payment_attempts + entitlements + buyers; CCAvenue adapter scaffold + LocalSimulatorProvider with real HMAC-signed payloads; ReconciliationService idempotent + amount/currency verified; signed-URL study downloads re-check entitlement on every request. 12 Phase-4 tests green, 23 total / 730 assertions. Merchant sandbox check still PENDING (kit not supplied). |
| 5 Migration & SEO rehearsal | done | 2026-11-10 | 2026-11-10 | Idempotent importers w/ --dry-run; migrate:rehearse proves zero delta on rerun (₹11,988 paid total stable); seo:audit + seo:redirect-map produce CSVs; 8 Phase-5 tests green, 31 total / 751 assertions. |
| 6 Release candidate & handover | pending | — | — | |

## Current state

- Laravel 13 + Blade + Tailwind 4 + Alpine scaffold.
- SQLite for local dev; MySQL 8.4 remains the staging/production target.
- Synthetic fixtures only — no production content, no customer data.
- CCAvenue is **not** wired. Provider adapter + local simulator land in Phase 4.
- Cloudflare R2 is **not** wired. Local disk (`storage/app/public` + `storage/app/private`) stands in until Phase 2.

## Outstanding owner inputs

Carried from PRD; none of these block Phase 1 but each must be resolved before launch.

- Domain + hosting access (Hostinger VPS target).
- Brand assets: licensed hero photo, book cover artwork, four task pictograms, licence records.
- Authorised offline legacy data extract (content, subscribers, orders, entitlements).
- Transactional-mail sender decision (SES vs Resend) + domain verification.
- CCAvenue merchant test credentials + integration kit.
- Confirmation on buyer-access policy, quantity-pricing rounding rule, manual shipment tracking.
- SMS provider decision (only if SMS delivery is required).

## Simulated / blocked items

Track separately from merchant sandbox evidence per PRD guardrails.

- All payments go through the local simulator until the merchant kit arrives.
- Mail uses the `log` / `array` transport until SES or Resend is chosen.
- File storage uses the `local` driver until R2 is provisioned.
- SEO baseline for the six legacy routes is captured from the field-review PDF, not from a live crawl (production access is explicitly out of scope).

## Session notes

### 2026-11-10 — Phase 1 kickoff
- Read PRD + rebuild plan PDF end-to-end.
- Confirmed PHP 8.3 in container is inside the Laravel 13 supported range (8.3–8.5).
- Chose SQLite as the local driver; migration files target MySQL-compatible syntax.
- Wrote route inventory, schema outline, permissions matrix (see sibling docs).
- Scaffolded Laravel, installed Tailwind 4 + Vite, built homepage + notice detail + book pages with synthetic fixtures.
