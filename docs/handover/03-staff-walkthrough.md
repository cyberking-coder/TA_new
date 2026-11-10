# Staff walkthrough (1–2 hour training)

Scripted walkthrough for the owner's content, operations and administrator staff. Assumes staff accounts have been created and MFA enrolled.

## Who does what

Per `docs/permissions-matrix.md`:

- **Administrator** — can do everything, including delete, staff user management, MFA reset.
- **Content editor** — pages, hero, newsline, PDFs, study material, books, exam names, moderate reviews. Cannot export, cannot send newsletters, cannot see orders.
- **Operations** — subscribers, result registration, newsletter send, orders (search, view detail, change dispatch). Cannot publish content.

## Logging in

1. Open `https://<domain>/admin/login`.
2. Enter your email + password.
3. Enter the 6-digit TOTP from your authenticator app. Save the recovery codes offline.
4. On the dashboard, confirm you see the three sidebar groups: **Content · People & Exams · Commerce**.

If a role does not see a group, that is intentional — not a bug.

## Publishing a notice (content editor)

1. Sidebar → **Content → Notices → New notice**.
2. Fill the fields; title auto-generates the slug.
3. Pick the exam and type.
4. Add source attribution — the source URL opens in a new tab on the public detail page.
5. Save as draft first and review the detail URL under `/admin/notices/{id}/edit`.
6. Back in the list, click **Publish** on the row. The homepage updates on the next visitor hit.
7. If the notice was wrong — click **Withdraw** on the row. The notice disappears from homepage, listing, detail page, and sitemap on the next request.

## Moderating a review (content editor)

1. Sidebar → **Commerce → Reviews**. Filter: `moderation_state = pending`.
2. Open each review. Approve or reject with the inline action.
3. Approved reviews appear on the book page under "Verified reviews". Pending and rejected reviews never show publicly.

## Looking up an order (operations)

1. Sidebar → **Commerce → Orders**.
2. Search box accepts order number, buyer name, mobile, or email.
3. Click the row to see shipping address, items, and payment attempts (with redacted raw responses).
4. To mark a parcel as shipped: change the dispatch status. Payment status is driven by the gateway and must not be edited manually (see `docs/phase-4-report.md`).

## Sending a newsletter (operations)

1. Sidebar → **People & Exams → Newsletters → New newsletter**.
2. Pick the audience (officer or soldier — the lists are strictly separate).
3. Write subject + HTML body.
4. Save. From the list, click **Send test** and enter your own email. Confirm it arrives correctly.
5. Click **Send to list**. A confirmation modal explains that suppressions are re-checked for every recipient at send time.
6. After send, the row shows `sent` count + `skipped_count`. `skipped_count` is addresses unsubscribed or suppressed between queue time and send time.

## Exporting subscribers or results (admin / operations)

1. Navigate to the list.
2. Filter as needed. Filters apply to the export.
3. Click **Export CSV** / **Export Excel (CSV)** in the header.
4. The file downloads. Every export is logged (`export_logs` table) with actor, filters, and row count.

Content editors cannot see the export button — this is intentional, not a bug.

## When something is wrong

- Content shows the wrong thing → check the Notice / Page / Book row's status (draft / published / withdrawn) and re-run **Publish** or **Withdraw**.
- Public PDF link gives 403 → the PDF is marked `private`; private PDFs are served only via the signed `/study/download/*` route to entitled buyers.
- Buyer cannot log in → confirm the order is `payment_status = paid`. Ask them for the order reference exactly as emailed. If they lost the setup link, admin can re-issue one from the Orders detail screen in a future build; for now, do NOT share their password or create an alternate account.
- Newsletter went to someone who had unsubscribed → check `Suppression::isSuppressed()`. If true, that address was skipped at send time and only the queue record exists. If false, their unsubscribe did not persist — escalate.

## Who to escalate to

- Payments: the owner's CCAvenue account manager.
- Mail delivery issues: the owner's SES/Resend admin.
- Server down: ops team per `docs/handover/02-environment-requirements.md`.

## Never do these

- Never mark an order `payment_status = paid` manually. Only gateway callbacks may do that.
- Never share plaintext passwords. There are none — all buyer and staff passwords are bcrypt hashed.
- Never upload a CSV to a "consignment" or "coupon" screen. These features are excluded from the build.
- Never test payment with a real card on the merchant sandbox. Use the owner-supplied test cards only.
