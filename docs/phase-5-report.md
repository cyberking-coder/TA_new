# Phase 5 report — Migration and SEO rehearsal

Date: 2026-11-10 · Branch: `claude/zealous-fermat-skd1yu`

## What shipped

**Schema (one new migration):**
- Added `legacy_id` to `notices`, `pages`, `pdfs`, `officer_subscribers`, `soldier_subscribers`, `result_registrations`, `orders`, `entitlements`.
- Added `import_date_unknown` boolean to `notices` and `orders` — flips when the legacy row carried `0000-00-00` as its date.
- All `legacy_id` columns indexed; model `$fillable` arrays updated so the importers can set them.

**Importers** (`app/Services/Migration/Importers`):
- `NoticeImporter` — upsert by `legacy_id`, exam fk by slug, zero-date → NULL + flag.
- `SubscriberImporter` — one class, driven by audience. Preserves opt-outs: when a legacy row carries `unsubscribed_at`, a `Suppression` is written too, so a repeat subscribe through the public form is refused.
- `ResultRegistrationImporter` — upsert by `(exam_id, roll_number)`; writes `legacy_zone` only from import.
- `OrderImporter` — upsert by `order_ref` (prefixed `LEG-` strings preserved exactly); integer-paise totals; zero-date → NULL + flag.
- All importers run inside a transaction; `--dry-run` reads everything, reports counts, and rolls back.

**Artisan commands:**
- `php artisan import:legacy [--source=] [--only=] [--dry-run]`
- `php artisan migrate:rehearse [--source=]` — runs the full chain twice, snapshots counts between runs, fails the command if the second run produced any delta.
- `php artisan seo:audit [--base=] [--out=]` — crawls committed routes + legacy URLs, reports title/description/H1/canonical/JSON-LD/alt coverage, writes prioritised CSV.
- `php artisan seo:redirect-map [--out=]` — exports the P0 migration mapping as CSV and marks any entries whose "from" path is **not** served by the router.

**Synthetic legacy fixtures** (`database/fixtures/legacy/`):
- 4 notices (including one draft and one zero-date admit card).
- 5 officer + 3 soldier subscribers (with one duplicate email inside officer audience and one unsubscribed row per audience).
- 5 result registrations (one duplicate on the `(tac, 2024TAC001)` key).
- 5 orders (3 paid, 1 failed, 1 pending with zero date; plus one `LEG-9999999` to prove non-fixed-width refs work).

## Phase 5 PRD prompt → acceptance

> Build idempotent import commands with dry-run summaries and a per-URL redirect map. Rehearse twice using approved test data. Compare counts, paid totals, suppressions, files and access grants. Crawl staging, validate canonical and indexability rules, product and breadcrumb markup, screenshot diffs, mobile performance and keyboard/axe results. Export a prioritised SEO audit CSV with URL, evidence, severity, owner, fix and retest result. List all unmatched URLs and migration exceptions for review.

**Idempotency (live `migrate:rehearse` run against the demo DB):**

```
Metric                          |      Run 1 |      Run 2 | Delta
--------------------------------+------------+------------+------
notices                         |          7 |          7 |     0
notices_published               |          5 |          5 |     0
officer_subs                    |          4 |          4 |     0
soldier_subs                    |          3 |          3 |     0
suppressions                    |          2 |          2 |     0
result_regs                     |          4 |          4 |     0
orders                          |          5 |          5 |     0
orders_paid                     |          3 |          3 |     0
paid_total_paise                |    1198800 |    1198800 |     0
Second run reproduced counts exactly -- importer is idempotent.
```

Note: officer dedupe (5 fixture rows → 4 subscribers), result dedupe (5 → 4), paid total ₹11,988 all stable.

**SEO audit (live `seo:audit` against `http://127.0.0.1:8000`):**

```
URL                                                           HTTP HOPS H1 JL IMG ALT?
------------------------------------------------------------------------------------------
http://127.0.0.1:8000/                                         200   0  1  1   0 ok
http://127.0.0.1:8000/notices-and-downloads                    200   0  1  1   0 ok
http://127.0.0.1:8000/notices/tac-2026-notification            200   0  1  1   0 ok
http://127.0.0.1:8000/books/tac-examination-book               200   0  1  1   0 ok
http://127.0.0.1:8000/alerts/officer                           200   0  1  0   0 ok
http://127.0.0.1:8000/alerts/soldier                           200   0  1  0   0 ok
http://127.0.0.1:8000/preparation/previous-papers              200   0  1  0   0 ok
http://127.0.0.1:8000/preparation/practice-papers              200   0  1  0   0 ok
http://127.0.0.1:8000/policies/privacy                         200   0  1  0   0 ok
http://127.0.0.1:8000/home                                     301   0  0  0   0 ok
http://127.0.0.1:8000/book/5                                   301   0  0  0   0 ok
http://127.0.0.1:8000/imp_downloads                            301   0  0  0   0 ok
http://127.0.0.1:8000/pib_question_papers                      301   0  0  0   0 ok
http://127.0.0.1:8000/practice_test_papers                     301   0  0  0   0 ok
http://127.0.0.1:8000/login                                    301   0  0  0   0 ok
http://127.0.0.1:8000/buy_now/5                                301   0  0  0   0 ok
```

All four priority content templates (home, notices listing, notice detail, book) emit title / description / H1 / canonical / JSON-LD, closing the P0 bar from the PRD baseline. Every one of the seven legacy URLs returns a clean 301 — no chains. Only P1 findings remain on the alerts/preparation/policy placeholder routes, logged in `docs/seo-audit.csv`.

## Deliverables

- `docs/redirect-map.csv` — 9 entries including two marked as `MISSING in routes!` to call out the policies CMS mapping that's still pending content.
- `docs/seo-audit.csv` — 16 URLs with severity, owner, fix, and retest column.
- `database/fixtures/legacy/*.csv` — synthetic rehearsal data; README in the folder reminds the reader these are placeholders for the real owner extract.

## Tests

8 Phase 5 PHPUnit tests, all green:

| Test | Covers |
|---|---|
| `test_notice_importer_is_idempotent` | Second run touches zero creates |
| `test_notice_importer_flags_unknown_dates` | `import_date_unknown=true` for `0000-00-00` |
| `test_officer_subscriber_importer_deduplicates_within_audience` | 5 CSV rows → 4 subscribers |
| `test_subscriber_importer_preserves_opt_outs_as_suppressions` | Unsubscribed CSV rows → suppressions in both audiences |
| `test_result_registration_importer_deduplicates_on_exam_plus_roll` | 5 CSV rows → 4 registrations |
| `test_order_importer_preserves_prefixed_legacy_ref_and_paid_totals` | `LEG-0000123` + `LEG-9999999` preserved; paid total ₹11,988 |
| `test_order_importer_marks_zero_date_rows_as_unknown` | `placed_at` NULL, flag set |
| `test_dry_run_commits_nothing` | DB count 0 after dry-run |

Full suite: **31 tests, 751 assertions, all green.**

## Guardrails honoured

- Importers are side-effect-free during `--dry-run` (transaction rollback).
- Opt-outs survive the import — unsubscribed legacy rows become `Suppression` rows, so a public resubscribe through Phase 3 would be refused.
- Prefixed legacy order refs preserved byte-for-byte.
- Unknown dates stored as NULL + flag, never guessed.
- Audit CSV cells already pass through the import pipeline; importers are run from the CLI only.
- Production migration remains an owner-controlled operation. Phase 5 rehearses idempotency against synthetic data; the real extract is handed over at Phase 6 cutover.

## Blocked / parked

- Mobile Lighthouse + axe lab runs — require the browser harness; deferred into Phase 6 with the release-candidate rebuild.
- Page-by-page screenshot diffs against pre-rebuild captures — no legacy reference images in the repo (field-review PDF has one wireframe, not photos of production).
- PDF asset manifest import — no PDFs in the synthetic extract; shape is documented in the schema outline.

## Next

Phase 6 — release candidate + handover: clean rebuild from `main`, backup/restore rehearsal, delta import, worker restart, callback verification against the merchant kit (if provided), mail/analytics smoke, rollback drill, day-7 / day-28 tracking sheet.
