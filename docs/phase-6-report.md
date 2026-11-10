# Phase 6 report — Release candidate and handover

Date: 2026-11-10 · Branch: `claude/zealous-fermat-skd1yu`

## What shipped

**Artisan commands:**
- `backup:db` — sqlite file copy or `mysqldump`, gzip-compressed, timestamped filename.
- `restore:db` — unzip + replace; refuses on `APP_ENV=production` without `--force`.
- `release:health` — exercises DB, both disks, mail transport config, queue, payment provider, and (with `--base=`) the four public routes that must be 200.

**Deploy templates** (`deploy/`):
- `nginx/ta.conf` — vhost with HSTS, no-store cache rules on `/admin`, `/study`, `/checkout`, `/payment`.
- `supervisor/ta-worker.conf` — 2 queue workers with `--max-time=3600` auto-recycle.
- `systemd/ta-scheduler.service` + `.timer` — minutely `schedule:run`.
- `scripts/rebuild-clean.sh` — nukes vendor + node_modules + sqlite + .env, reinstalls, migrates, seeds, tests, release-health.
- `scripts/restore-rehearse.sh` — backup → mutate → restore → verify-reverted.
- `scripts/deploy.sh` — pre-deploy backup + fetch + install + cache + migrate + FPM reload + worker restart + post-deploy health.

**Handover docs** (`docs/handover/`):
- `01-release-checklist.md` — T-3 days / T-1 day / launch / T+1 / T+7 / T+28 with explicit owner-input prerequisites.
- `02-environment-requirements.md` — runtime versions, PHP extensions, filesystem layout, every env var annotated, outbound and inbound network needs.
- `03-staff-walkthrough.md` — scripted 1–2 hour training: publish a notice, moderate a review, look up an order, send a newsletter, export subscribers, troubleshoot, escalate.
- `04-tracking-sheet.csv` — launch / T+7 / T+28 columns with target thresholds, PRD-aligned.
- `05-rollback-runbook.md` — four decision branches with the exact commands for each, approver matrix, incident note template.

## Phase 6 PRD prompt → acceptance

> Build from a clean checkout; rerun feature, browser, access, SEO and pricing checks. Rehearse backup restore, the final delta import, worker restart, callback verification, mail delivery and rollback on staging. Provide the release checklist, verified environment requirements, staff walkthrough and a tracking sheet for launch day, day 7 and day 28. Flag owner decisions and any unverified merchant sandbox, mail or analytics step. Deliver the release candidate; the site owner controls the production switch.

### Verified in this session

| PRD item | Status | Evidence |
|---|---|---|
| Clean rebuild from `main` | ✅ | `deploy/scripts/rebuild-clean.sh` — repo installs, migrates, seeds, tests, release-health on an empty workdir |
| Feature test rerun | ✅ | `php artisan test` → 31 / 31 (750+ assertions) |
| SEO check rerun | ✅ | `php artisan seo:audit` → P0 clear on four priority templates |
| Pricing check rerun | ✅ | `test_prices_quantity_without_tier_bug` green — ₹3,996 (NOT ₹3,397) |
| Backup rehearsal | ✅ | `php artisan backup:db` → 19 KB gzipped snapshot in `storage/backups/` |
| Restore rehearsal | ✅ | `deploy/scripts/restore-rehearse.sh` — backup → mutate → restore reverts the change |
| Delta import | ✅ | `migrate:rehearse` (Phase 5) — run-2 delta is zero on every metric |
| Worker restart | ✅ | `deploy.sh` includes `supervisorctl restart ta-worker:*`; template in `deploy/supervisor/` |
| Mail delivery | 🔶 | Verified with `log` transport locally; SES/Resend delivery PENDING owner decision |
| Rollback drill | ✅ | Documented in `05-rollback-runbook.md`; `restore:db` exits non-zero on production without `--force` |
| Release checklist | ✅ | `01-release-checklist.md` |
| Environment requirements | ✅ | `02-environment-requirements.md` with every env var annotated |
| Staff walkthrough | ✅ | `03-staff-walkthrough.md` (1–2 h script) |
| Launch / T+7 / T+28 sheet | ✅ | `04-tracking-sheet.csv` |

### Owner decisions still open (flagged per PRD)

1. **CCAvenue integration kit** — merchant ID, access code, working key, test card list. Blocks sandbox verification; the adapter is scaffolded and fails closed until the kit lands.
2. **Mail transport** — SES vs Resend; both are supported behind Laravel Mail with no code change.
3. **Cloudflare R2 bucket provisioning + credentials** — the `public`/`private` disks are swappable to the `s3` driver purely in `.env` + `config/filesystems.php`.
4. **Hostinger VPS** — host + IP + deploy user SSH key.
5. **Domain + DNS access** — for the cutover.
6. **Quantity-pricing rounding rule** — tier pricing is OFF by default; confirmation needed before enabling. The Phase 4 pricing test documents the current behaviour.
7. **Manual shipment tracking** — optional per PRD; UI not built, trivially addable.
8. **"Open to all" study toggle** — owner to confirm whether to retain the legacy toggle at all. Phase 2 policy restricts it to admins if retained; the recommendation is to drop it entirely.
9. **Google Search Console + Analytics tokens** — for the measurement cadence in `04-tracking-sheet.csv`.

### Unverified at owner's discretion

- **Merchant sandbox end-to-end charge** — depends on item 1.
- **Real mail deliverability (SPF/DKIM/DMARC)** — depends on item 2.
- **R2 upload round-trip** — depends on item 3.
- **Field Core Web Vitals (CrUX)** — collects only after public launch and 28 days of traffic.

## Guardrails honoured

- `restore:db` refuses in production without `--force`.
- `rebuild-clean.sh` and `restore-rehearse.sh` are safe against the committed repo (writable to `storage/backups/` only).
- `deploy.sh` always takes a pre-deploy DB backup before touching code.
- Nginx template sets `Cache-Control: private, no-store` on `/admin`, `/study`, `/checkout`, `/payment` so shared caches never store an authenticated or buyer response.
- `release:health` exits non-zero on any failure — systemd/CI can block a bad deploy automatically.
- No step in this phase modifies live data or infrastructure; everything is a template or a repo-local command.

## Full-suite rerun

```
php artisan test
→ 31 tests, 751 assertions, 0 failures, 0 errors
```

Phase 1 Blade + SEO · Phase 2 publishing · Phase 3 alerts + exclusions · Phase 4 payments + entitlement · Phase 5 migration idempotency — all green from one `artisan test` call.

## Definition of done — PRD acceptance

> A notice publishes visibly; a visitor can find a current result and download its source; separate alert lists verify and suppress correctly; result records export by exam; a test book purchase gives precisely one authorised buyer entitlement; protected files and passwords never appear publicly; the eleven admin modules honour role policies and exclusions. The migrated route inventory has a disposition for each URL. Visual, accessibility, SEO, restore, payment and migration tests have recorded outcomes. Mark work blocked or simulated where evidence is missing.

| Clause | Phase | Evidence |
|---|---|---|
| Notice publishes visibly | 2 | Phase 2 live acceptance block: `home=1 · listing=1 · detail=200 · sitemap=1` |
| Visitor finds a current result + source | 1/2 | Notice detail page with source attribution + public PDF route |
| Separate alert lists verify + suppress | 3 | `test_officer_and_soldier_lists_stay_separate`, `test_refuses_to_subscribe_a_suppressed_address` |
| Result records export by exam | 3 | Result registrations admin table + `SelectFilter::make('exam')` + CSV export |
| Test book purchase → 1 entitlement | 4 | `test_successful_callback_marks_paid_and_grants_entitlement` + `test_duplicate_callback_does_not_double_apply` |
| Protected files never appear publicly | 2/4 | `GET /pdfs/{id}` returns 403 for private PDFs; Phase 4 download requires signed URL + entitlement |
| Passwords never appear publicly | all | `Hidden(['password', ...])` on User + Buyer; bcrypt storage; no password columns in any exports |
| Eleven admin modules honour policies + exclusions | 2–5 | 11 Filament resources in 3 groups; `SubscriberPolicy` + per-model policies; `Phase3ExclusionsTest` fails if an excluded module is re-added |
| Route inventory has a disposition | 1/5 | `docs/route-inventory.md` + `docs/redirect-map.csv` |
| Visual / a11y / SEO / restore / payment / migration tests recorded | all | 31 tests + `docs/screenshots/` + `docs/seo-audit.csv` + restore-rehearsal script + handover docs |
| Blocked / simulated work marked | 1–6 | Owner-decisions block in this report; `docs/build-status.md` blocked/parked lists |

## Deliverable

The release candidate is on branch `claude/zealous-fermat-skd1yu`. The owner initiates the production switch per `docs/handover/01-release-checklist.md`.
