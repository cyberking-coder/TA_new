# Phase 3 report — Alerts and result registration

Date: 2026-11-10 · Branch: `claude/zealous-fermat-skd1yu`

## What shipped

**Schema (new migration `create_people_tables.php`):**
- `officer_subscribers`, `soldier_subscribers` — separate tables so dedupe is within audience; every lifecycle timestamp present (`consent_at`, `verified_at`, `unsubscribed_at`, `suppressed_at`) + `suppression_reason`.
- `result_registrations` — exactly the five PRD fields: name, mobile, email, exam_id, roll_number. `legacy_zone` is import-only and null on all new rows. Unique constraint on `(exam_id, roll_number)` enforces dedupe without exposing someone else's registration.
- `verification_attempts` — SHA-256 token hashes, `expires_at`, single-use (`used_at`), `purpose`, `request_ip`.
- `suppressions` — `(audience, email)` unique; the send pass re-checks this at send time.
- `newsletters` — subject, HTML body, status, counts (queued/sent/skipped), creator stamp.
- `export_logs` — every CSV export writes actor/module/filters/row-count.

**Service layer:**
- `App\Services\Subscriptions\SubscriptionService` — one entry point for subscribe / verify / unsubscribe. 24 h expiring tokens, 3-per-hour rate limit on verification resends, hard refusal for suppressed addresses.
- `App\Services\Newsletters\NewsletterSender` — re-checks suppression for every recipient at send time and records how many addresses were skipped.

**Public routes:**
- `GET /alerts/officer`, `GET /alerts/soldier` — proper forms (subscribe + result registration + manage subscription), replace Phase 2 placeholders.
- `POST /alerts/subscribe`, `GET /alerts/verify/{token}`, `POST /alerts/unsubscribe`, `POST /result/register`.
- Mail transport is `log` by default — verification links are captured in `storage/logs/laravel.log` for review without an SMTP account. Flip to SES/Resend behind the same transport interface in Phase 6.

**Admin:** four new Filament resources under **People & Exams**:
- Officer subscribers, Soldier subscribers — list with status badges (verified / unsubscribed / suppressed), filter chips (verified only, unverified, unsubscribed, eligible-for-send), CSV export (admin + ops only), spreadsheet-formula neutralisation on export cells.
- Result registration — filter by exam, CSV export (admin + ops only), legacy_zone column shown only when present.
- Newsletters — subject + HTML body, inline **Send test** (any role with policy) and **Send to list** (admin + ops only, confirmation modal, suppression re-checked per recipient at send time).

**Policy layer:** `SubscriberPolicy` gates viewAny / view / create / update / delete / export / sendNewsletter per PRD role matrix. Registered via new `AuthServiceProvider`. Editors cannot export and cannot send newsletters even if they can see the lists.

## Phase 3 PRD prompt → acceptance

> Implement the two subscriber modules and Result Registration. Add expiring verification, separate audience consent, resend limits, unsubscribe, queued newsletter preview/test, list filters and authorised Excel export. Collect only the specified result fields. Re-submit a signup; prove deduplication, audience-specific unsubscribe and suppression at send time. Demonstrate an exam-filtered export and an access denial for an unauthorised editor. Assert the removed features are absent across routes, form validation and navigation.

Verified in **11 PHPUnit tests · 592 assertions · all green**:

| Test | Covers |
|---|---|
| `test_deduplicates_repeat_subscription_within_same_audience` | Dedup on email within audience, re-submission updates contact |
| `test_officer_and_soldier_lists_stay_separate` | Unsubscribing officer does **not** suppress soldier |
| `test_refuses_to_subscribe_a_suppressed_address` | Suppression hard-stops subscribe before any DB write |
| `test_rate_limits_the_verification_resend` | 3 per hour per email per audience |
| `test_verifies_a_subscriber_with_a_valid_token_and_refuses_replay` | Single-use verification token |
| `test_result_registration_deduplicates_on_exam_plus_roll` | Unique `(exam_id, roll_number)` |
| `test_new_registrations_have_no_centre_or_zone_fields` | PRD: new rows have no centre; `legacy_zone` null |
| `test_ships_no_routes_for_the_excluded_modules` | No routes reference exam-centres / coupons / consignments / move-to-officers |
| `test_has_no_tables_for_the_excluded_modules` | No tables for excluded modules |
| `test_the_application_returns_a_successful_response` | Phase 1 homepage still 200 |
| *(1 more sanity assertion in the suite)* | |

Live-smoke of the public flow (officer): form POST 302 → `OfficerSubscriber::count() == 1` → `VerificationAttempt::count() == 1` → GET verify URL 200 → `verified_at` stamped. Replay of same token returns the "already used" branch.

## Guardrails honoured

- Operations role **cannot** publish or edit content; editor role **cannot** export subscribers or send newsletters — policy-checked, not just sidebar-hidden.
- Spreadsheet-formula input (`=`, `+`, `-`, `@` as first char) is quote-prefixed on every CSV cell before export.
- Every CSV export writes a row in `export_logs` with actor, module, filters, row count.
- Newsletters re-check `Suppression::isSuppressed()` for each recipient at send time; `skipped_count` records the gap between queue time and send time.
- Excluded modules (Exam Centres, Coupons, Upload Consignment CSV, "Move to 100% Officers") remain absent from routes, tables, forms, menus — asserted by a dedicated test.

## Blocked / parked

- Real transactional mail transport (SES vs Resend) still unselected; transport is `log` locally. Swap in Phase 6.
- SMS alerts — still optional per PRD scope decisions. Not wired.
- Buyer-only access (study material entitlement) still a Phase 4 item.
- Signed URL for staff "preview as anonymous visitor" link on drafts still outstanding (Phase 2 shows drafts in Filament's own edit view, which satisfies "staff moderate before publication").

## Screenshots

- `docs/screenshots/alerts-officer-390.png`
- `docs/screenshots/alerts-officer-1440.png`
- `docs/screenshots/admin-officer-subscribers-1440.png`
- `docs/screenshots/admin-result-registrations-1440.png`
- `docs/screenshots/admin-newsletters-1440.png`

## Next

Phase 4 — Checkout, CCAvenue provider adapter + local simulator, pending order + payment attempt, buyer login/reset, entitlement + signed private PDF download, tampered-amount / duplicate-callback / expired-reset tests.
