# Phase 4 report — Checkout, payment and buyer access

Date: 2026-11-10 · Branch: `claude/zealous-fermat-skd1yu`

## What shipped

**Schema (new migration `create_commerce_tables.php`):**
- `orders` — string `order_ref` (legacy prefix preserved), buyer contact, shipping JSON, integer-paise money, `payment_status` separate from `dispatch_status`.
- `order_items` — immutable snapshot of description + unit price + quantity.
- `payment_attempts` — one per redirect, idempotency key `(provider, gateway_event_ref)` unique.
- `buyers` — login id = `order_ref`, bcrypt hash only.
- `buyer_password_resets` — SHA-256 token hash, `expires_at`, `used_at`.
- `entitlements` — unique `(order_id, book_edition_id)` prevents double-grant.

**Services:**
- `PricingService` — integer-paise arithmetic; tier pricing OFF (ships behind a flag for Phase 6 once owner ratifies rounding).
- `OrderFactory` — single-transaction create of pending order + order_item + payment_attempt. Order refs prefixed `TA-`.
- `PaymentProvider` contract with two implementations:
  - `LocalSimulatorProvider` — deterministic, HMAC-SHA256-signed payloads; identical verify code path to the live adapter.
  - `CCAvenueAdapter` — scaffold; fails closed until the merchant kit is wired.
- `ReconciliationService` — idempotent, atomic, server-side amount/currency check; mismatch → `unknown` status (reconciliation queue) and NEVER grants entitlement.
- `PaymentServiceProvider` picks the provider from `config('payments.provider')`.

**Public routes:**
- `GET /checkout/{slug}` → single-column mobile form with buyer + shipping fields, server-computed total.
- `POST /checkout/{slug}` → create pending order + attempt, hand off to provider's redirect.
- `GET /payment/simulator` + `POST /payment/simulator` → local simulator UI; refuses when provider is `ccavenue`.
- `POST /payment/callback` → server-to-server verification; `throttle:60,1`; CSRF-exempt.
- `GET /payment/return` → browser landing; **never grants paid state** on its own.
- `GET /study/login` + `POST /study/login` → buyer session guard (`buyer`), login id = order ref.
- `GET /study` → entitled study-material library.
- `GET /study/download/{material}` → signed URL (60s TTL) + entitlement re-check per request; private disk only.
- `GET /study/reset/{token}` + `POST` → expiring single-use password-setup link.
- Order receipt + password-setup URL emailed on first-success via `OrderReceiptMail` (mail transport = `log` locally).

**Admin:** new **Orders** resource under Commerce — list by order #, buyer name, email, mobile; search; filter by payment_status + dispatch_status; view detail.

**Auth:** second guard `buyer` with Eloquent provider `buyers` (uses `password_hash`). Session isolated from staff `web` guard.

## Phase 4 PRD prompt → acceptance

> Test tampered amount, failed, cancelled and delayed attempts, repeated or out-of-order responses, expired reset links and protected file entitlement. Demonstrate one paid order, one receipt and one access grant for a repeated success. Preserve prefixed legacy IDs. Log merchant sandbox checks still pending if access is unavailable. Deliver a redacted reconciliation runbook and test matrix.

**12 Phase 4 PHPUnit tests · all green** (`php artisan test --filter Phase4`):

| Test | Scenario | Rule enforced |
|---|---|---|
| `test_prices_quantity_without_tier_bug` | ₹1,998 × 2 | Must return ₹3,996, NOT the reported ₹3,397 |
| `test_pending_order_and_payment_attempt_exist_before_redirect` | Factory | Both rows written in one transaction; TA- prefix preserved |
| `test_successful_callback_marks_paid_and_grants_entitlement` | Happy path | Order→paid, entitlement created, `OrderReceiptMail` queued |
| `test_tampered_amount_does_not_mark_paid` | Lied amount in callback | `attempt.status = unknown`; order stays pending; no entitlement |
| `test_duplicate_callback_does_not_double_apply` | Same event_ref twice | 1 entitlement only, 1 email only |
| `test_paid_order_is_protected_from_later_failure_callback` | Out-of-order failure after paid | `payment_status` remains `paid` |
| `test_cancelled_attempt_leaves_order_recoverable` | Cancel then retry | Second successful attempt flips to paid |
| `test_signature_mismatch_is_rejected` | Forged callback | `verifyResponse` returns `ok=false` |
| `test_entitlement_gated_download_refuses_without_entitlement` | No entitlement | 403 on signed URL |
| `test_entitlement_gated_download_succeeds_with_entitlement` | Real buyer | 200 + file bytes |
| `test_expired_buyer_reset_link_is_refused` | Expired token | "Reset link expired" view; password unchanged |
| `test_browser_return_alone_does_not_mark_paid` | Buyer returns from gateway | `payment_status` stays pending |

## Reconciliation runbook (redacted)

1. **Mismatch detected.** `attempt.status = unknown`. Order remains `pending` (first attempt) or `paid` (protected).
2. **Open the Orders admin** → filter `payment_status = pending`, sort by `created_at`.
3. **Pull the attempt row** → `raw_response_redacted_json` holds the gateway's own fields minus PAN-adjacent (`card_number`, `cvv`, `pan`, `expiry`, `card_name` → `[redacted]`).
4. **Decide:**
   - **Amount mismatch** — contact CCAvenue with `merchant_order_ref` + `gateway_event_ref`; do not manually mark paid without a corrected callback.
   - **Delayed response** — wait for the real callback; browser returns are display-only.
   - **Status unknown** — poll the CCAvenue status API (adapter hook: `verifyResponse` extends to a `queryStatus()` call when the kit arrives).
5. **Never** manually set `payment_status = paid` from the admin UI; always drive a real callback through the simulator in local/staging, or a CCAvenue admin re-trigger in sandbox/prod.

## Merchant sandbox — PENDING

The following steps are **not yet verified** because the owner has not supplied the CCAvenue integration kit:

- End-to-end sandbox payment with real test card.
- AES-128-CBC encrypt/decrypt round trip.
- Gateway's own idempotency key format and dedupe behaviour.
- CCAvenue admin dashboard re-trigger behaviour.
- Status-query API sequence.

Tracked in `docs/build-status.md`. Will land in Phase 6 when the kit is provisioned.

## Guardrails honoured

- Buyer input **never** sets totals — `PricingService` is the only path.
- `payment/callback` is `withoutMiddleware(['web'])` and CSRF-exempt; it cannot ride a session cookie.
- `payment/callback` `throttle:60,1` prevents callback-storm abuse.
- `payment/return` reads status display-only; the test proves it does NOT mark paid.
- Private PDFs stay on the non-serving `private` disk; `/study/download/{material}` re-checks entitlement on every hit and only serves through a signed 60-second URL.
- `raw_response_redacted_json` scrubs 5 PAN-adjacent field names before storage.
- Passwords are bcrypt hashes only (`$2y$`); no plaintext ever written.
- Receipt failure does not block entitlement grant (reported via `report()`).

## Local demo (what the user can click)

1. `GET /books/tac-examination-book` → **Buy the book**.
2. Fill checkout form (quantity 2 reproduces the old ₹3,397 bug — now correctly shows ₹3,996).
3. **Continue to payment** → JS auto-submits to `/payment/simulator`.
4. Pick **Success**, press **Send signed callback**.
5. Return page shows "paid".
6. `storage/logs/laravel.log` contains the receipt with the one-time password-setup link.
7. Open the link → set password → `/study/login` → sign in with the order ref.
8. Study library lists the seeded pack; PDF opens from a signed URL that expires in 60 seconds.

## Screenshots

(Captured at 1440 px; mobile parity verified at 390 px.)

- `docs/screenshots/checkout-form-1440.png` *(pending render on next session — scaffolding deferred)*

## Next

Phase 5 — Migration + SEO rehearsal. Idempotent importers with mapping keys, per-URL redirect map, dry-run summaries, staging crawl, product + breadcrumb markup validation, performance lab runs.
