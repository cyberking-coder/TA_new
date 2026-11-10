# Schema outline

First-pass table list. Column-level definitions land with each phase's migrations. Money is integer paise. Legacy order references are strings with any prefix preserved.

## Content

- `pages` — id, slug (unique), parent_id (nullable self-fk), menu_label, title, body (longtext), seo_title, seo_description, status (draft/published), published_at, author_id, timestamps
- `page_redirects` — id, from_path, to_path, hop_count, created_at (redirect history on slug change)
- `notices` — id, slug, title, summary, body, exam_id (fk), published_on (date), publication_state, source_label, source_url, pdf_id (nullable fk), author_id, timestamps
- `exams` — id, name, slug, short_code, timestamps
- `pdfs` — id, title, disk (`public`/`private`), path, size_bytes, mime, download_label, timestamps
- `study_materials` — id, title, slug, pdf_id, exam_id, book_edition_id (fk to grant entitlement), description, published_at, timestamps
- `reviews` — id, book_id, author_name, rating, body, moderation_state (pending/approved/rejected), moderated_by, moderated_at, timestamps
- `hero_media` — id, image_path, headline, subhead, action_label, action_url, is_featured, timestamps
- `newsline_items` — id, title, destination_url, published_at, order, timestamps

## People

- `staff_users` — id, name, email, password_hash, role (admin/editor/ops), mfa_secret, timestamps
- `officer_subscribers` — id, email (indexed), name, mobile, consent_at, verification_token, verified_at, unsubscribed_at, suppressed_at, timestamps; unique (email) within list
- `soldier_subscribers` — same shape, separate table for clean list separation
- `result_registrations` — id, name, mobile, email, exam_id, roll_number (string), legacy_zone (nullable string, for historical export only), created_at
- `verification_attempts` — id, subscriber_type, subscriber_id, token_hash, expires_at, used_at (single-use, rate-limited)
- `export_log` — id, actor_staff_id, module, filters_json, row_count, created_at

## Commerce

- `books` — id, slug, title, author, description, cover_path, status, timestamps
- `book_editions` — id, book_id, edition_name, price_paise, stock_state, delivery_blurb, timestamps
- `orders` — id, order_ref (string, preserves legacy prefix), buyer_name, buyer_mobile, buyer_email, shipping_json, payment_status (pending/paid/failed/refunded), dispatch_status (unshipped/shipped/delivered), subtotal_paise, discount_paise, shipping_paise, total_paise, currency (`INR`), placed_at, paid_at, timestamps
- `order_items` — id, order_id, book_edition_id, item_description_snapshot, unit_price_paise, quantity, line_total_paise
- `payment_attempts` — id, order_id, provider (`ccavenue`), merchant_order_ref, amount_paise, currency, status, gateway_event_ref (unique), raw_response_redacted_json, created_at
- `entitlements` — id, order_id, buyer_id (nullable until recovery), book_edition_id, granted_at, revoked_at
- `buyers` — id, order_ref (login id), email, password_hash, last_login_at, timestamps
- `password_resets` — id, buyer_id, token_hash, expires_at, used_at

## Audit / ops

- `jobs`, `failed_jobs`, `job_batches` (standard queue tables)
- `audit_log` — id, actor_type, actor_id, action, subject_type, subject_id, context_json, created_at (publishing, exports, payment transitions, access grants)

## Integer paise + rounding

All money is stored and operated on in integer paise. The **approved rounding rule** (to be ratified by the owner before Phase 4) is: compute each line in paise, sum exactly, then round the final total to the nearest whole paisa using banker's rounding. The existing `₹3,397 on a ₹1,998 unit price` defect is a direct test case.

## Preserved legacy behaviour

- Order references are strings, prefix preserved.
- Historical zone values are kept for one export only; new result registrations collect none.
- Invalid historical dates import as `NULL` with a dedicated `import_date_unknown` flag.
- Password hashes only. Any importer that encounters a plaintext column drops the value and forces a reset flow.
