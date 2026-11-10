# Territorial Army exam information and bookshop rebuild
Product requirements and Claude Code implementation brief • 10 November 2026

## Start here

Place this file at the root of the project repository. Paste the following prompt into Claude Code from that directory. Each phase below also has a standalone prompt for resuming work.

> Read Territorial_Army_Claude_Code_PRD.md and any applicable repository instructions. Implement the six phases in order for a working staging website and admin. Inspect the repository, preserve useful existing code and choose the smallest implementation meeting the requirements. Start with Phase 1 and continue through the remaining phases when their dependencies are available. At each phase show the changed files, exact commands run, test results and outstanding dependencies. Use synthetic fixtures and a local payment simulator until authorised CCAvenue test credentials and the merchant integration kit are available. Keep simulator evidence distinct from merchant sandbox evidence. Do not connect to the legacy production admin, scrape customer records or deploy to production. Produce a working staging build and a tested launch runbook. Track progress in docs/build-status.md so the next session can resume.

## Product and scope

Serve visitors who want a recent notice or result, alerts by audience, a preparation book, or materials included with a purchase. Identify the operator and purpose clearly. Use source-attributed notices and verified external links for official recruitment actions. Copy and artwork must be owned, licensed or supplied by the client. Proposed schedule is six weeks around the existing ₹50,000 development baseline. Technical SEO is specified here; ongoing content campaigns, paid SEO tools, provider charges and optional add-ons need separate costing. Owner inputs include domain and hosting access, brand assets, rights to the book cover and photos, authorised legacy data, transactional-mail sender, merchant test kit, and confirmation of buyer access and quantity-pricing rules.

**Eleven admin modules:** Pages, Slider/hero media, Newsline & Reviews, PDFs, Study Material, Books, Exam Names, Result Registration, Officer Subscribers, Soldier Subscribers, Orders. Group the sidebar as Content, People & Exams, Commerce. Permit a single featured hero image as the initial slider setting. Staff moderate reviews before publication. Support restricted Excel exports, order search by number/mobile/name, separate officer/soldier lists, subscriber status and unsubscribe handling. Admins can send newsletters only to eligible verified recipients for the selected list, with preview, test delivery, unsubscribe and suppression checks. The staging mail transport captures test messages.

**Explicit exclusions:** exam centres, consignment uploads including CSV, coupons, and the legacy “Move to 100% Officers” operation. Exclude their tables for new operations, routes, menu entries, permissions, fields and background imports. Neither public forms nor checkout may depend on them. Preserve existing order amounts and historical data needed for reconciliation. Only an existing stored zone may be used for historical zone exports. New exam registrations collect name, mobile, email, exam and roll number. Individual manual shipment tracking is optional pending owner confirmation. Automatic quantity pricing is a separate book rule; leave it disabled until approved.

## Technology decisions

| Layer | Implementation | First proof |
|---|---|---|
| Application | PHP 8.4, Laravel 13, server-rendered Blade views, Eloquent and MySQL 8.4. One repository and shared domain model. | Composer resolves on the target PHP runtime; empty database migrates and seeds cleanly. |
| Public interface | Tailwind CSS 4.1+, Vite, self-hosted Barlow and Source Sans 3, minimal Alpine where disclosure controls need it. Use normal links and server-rendered text. | With JavaScript disabled, links, notices and core form submission still work. |
| Admin and access | Filament 5 panel, Laravel policies, separate staff authentication with MFA. Roles: administrator, content editor, operations. | Editor can publish a notice; access to private orders, exports and payment actions is denied. |
| Files and jobs | S3-compatible Cloudflare R2 for media and PDFs; distinct public/private locations, signed short-lived private downloads. Database-backed queue and scheduler for email and reconciliation. | A protected URL requires buyer entitlement; a failed job is observable and safely retryable. |
| Payments and email | CCAvenue merchant-hosted checkout, provider adapter and test double. Laravel Mail with one configured transport (SES or Resend). | Success, failure and repeated notifications are tested; email appears once in staging mail capture. |
| Hosting and QA | Proposed Hostinger VPS, Nginx, PHP-FPM, HTTPS, supervised queue worker, scheduled jobs, off-host backups. PHPUnit or Pest per repo convention, Playwright, axe, Lighthouse CI. | Clean install, restore rehearsal, desktop/mobile smoke suite and reproducible production asset build. |
| Measurement | Search Console, consent-aware GA4 or equivalent, crawl report and field performance where data exists. | Analytics events carry no names, phones, emails, roll numbers or order IDs. |

Resolve and commit Composer and npm lockfiles. Verify runtime and extension support before locking the deployment target. Use public caching for published content only; exclude authenticated, checkout, callback and private file responses from shared caches. Document local, staging and production environment variables in an example file with dummy values.

## Simpler visual interface

Design direction: a modern editorial exam guide. Forest green #173D32 on warm ivory #FAF8F2, charcoal #1E2926 for body, muted ochre #B18237 as a decorative accent. Use dark green on white for action buttons. Barlow Semibold headings, Source Sans 3 body, comfortable 16–18 px base text. Test actual foreground/background pair contrast. Use authentic, rights-cleared photography and the real book cover; record source, licence, crop and alt text. Use a neutral, labelled placeholder during development if rights are pending.

| Screen | Components and behaviour |
|---|---|
| Homepage | Compact header with Notices, Prepare, Books and Study login. Hero with a landscape photo or training scene, a short purpose statement, and one primary action “View latest notices”. Follow with four task links, three latest dated notices with “View all”, a book-cover feature and utility footer. Move history to its own linked page. On mobile put headline and action before the image. |
| Notices and results | A dated list with exam/year/type filters, accessible labels and reset. Each notice has a permanent HTML detail page with summary, exam, publication date, source attribution, download type/size and related papers. Distinguish official source links from registration for alerts. |
| Preparation and papers | Visual subject/year groupings, resource type icons with text labels and editorial thumbnails only when meaningful. Keep previous papers and practice papers distinct. Private study files appear only after entitlement. |
| Book and checkout | Real cover, edition, visible price in INR, what the buyer receives, delivery and study-access information, genuine moderated reviews and one Buy button. Single-column mobile checkout with persistent field values on errors and a visible order summary before payment. |
| Alerts and login | Separate officer and soldier choices, plain-language verification and unsubscribe states. Result signup asks for contact details, exam and roll only. Study login includes order-ID guidance, password reset and a clear access-denied state. |
| Staff workspace | Group the 11 modules into three sidebar groups. Default order view prioritises order number, status, total and date. Open a detail screen for shipping/contact data. Publishing screens offer draft, preview and publish with clear result feedback. |

Use one editorial hero, one actual book image and a coherent family of four task pictograms as the first asset set. Reserve figure dimensions, supply responsive sizes in WebP/AVIF plus a fallback, load lower-page graphics lazily, and prioritise the visible hero. Avoid autoplay, moving tickers and text baked into images. Draft visual budgets: mobile hero ≤180 KB, initial public route JS ≤100 KB compressed. Test at 360, 390, 768 and 1440 px, 200% zoom, keyboard-only, reduced motion, visible focus, form errors and empty states. Interaction target 44 × 44 CSS px where practical. Save review screenshots for home, notices, book, checkout and admin using synthetic data.

## Data, rules and integration contracts

- Publishing: pages have a unique slug, parent, menu label, publication state, body and SEO fields. Notices include exam, date, summary and source/file. A draft stays private. Publishing refreshes public listings, cache and sitemap. Unpublishing removes it from each. Keep redirect history when slugs change.
- People: index subscriber audience plus normalised email; verification codes expire, are rate-limited and single-use. Keep consent, list-specific verification, unsubscribe and suppression timestamps. Gate queued newsletters again at send time. Store exam ID plus roll as strings where relevant; handle duplicate submissions without exposing someone else's registration. Restrict export through policies, log actor/time/filter and neutralise spreadsheet-formula input.
- Commerce: books and editions own prices; snapshot item description, unit price, quantity, shipping, discount and final INR amount on the order. Use integer paise and an approved rounding rule. Buyer input cannot set totals. Preserve legacy order IDs including prefixes. Distinguish payment status from dispatch status.
- Payment: create pending order and payment attempt before redirect. Handle the merchant-approved response mechanism according to the supplied kit. Verify authenticity, merchant order reference, amount and currency server-side. A database transaction plus a unique gateway event/transaction reference prevents duplicate paid transitions, emails and entitlements. Unknown, delayed or mismatched responses enter reconciliation. A browser return alone cannot grant access. Protect confirmed payments from a late failed attempt. Store redacted payment diagnostics.
- Buyer access: grant only the approved book/edition entitlement after verified success. Recovery uses expiring single-use reset links. Use salted password hashes; omit passwords from views, imports, exports and logs. Historical users use secure recovery. Operations cannot open all study materials globally; if the owner retains the legacy public-access toggle, restrict, audit and test its effect on every private download route.
- Migration: use an authorised offline extract. Import content/files, exams, subscribers and opt-outs, registrations, order history and eligible entitlements in dependency order. Use mapping keys so reruns are idempotent, preserve money/legacy references, flag invalid dates as unknown, and validate counts plus totals by status. Rehearse with synthetic or de-identified data; keep the production export outside source control.

## SEO audit and priorities

**Measured baseline, 10 November 2026.** Browser inspection of the rendered document on six public routes. “Missing alt” means no alt attribute. Image counts are occurrences and include shared layout assets. A zero JSON-LD count refers only to script elements of that type. This sample does not establish sitewide indexing, Google-selected titles, traffic or rankings.

| Path | Nonempty document title | Meta description | H1 | HTML canonical | JSON-LD blocks | Images missing alt / total |
|---|---|---|---:|---|---:|---:|
| / | No | Absent/empty | 0 | None | 0 | 14/14 |
| /home | No | Absent/empty | 0 | None | 0 | 14/14 |
| /book/5 | No | Absent/empty | 0 | None | 0 | 7/7 |
| /imp_downloads | No | Absent/empty | 0 | None | 0 | 4/4 |
| /pib_question_papers | No | Absent/empty | 0 | None | 0 | 4/4 |
| /practice_test_papers | No | Absent/empty | 0 | None | 0 | 4/4 |

**P0 • Canonical URLs and accessible HTML.** Use one absolute canonical, unique title, useful description and one descriptive primary H1 for each indexable HTML detail or hub. Update navigation to final destinations. Keep a short meaningful title, report duplicates and empty values in CI. Use descriptive links in place of repeated “Click Here”. Add descriptive alt to informative photos/covers; empty alt is appropriate for decorative imagery.

**P0 • Migration mapping.** Proposed targets below must be tested against the actual inventory before launch. Preserve a stable public PDF URL if it already has external links. Redirect a moved public PDF to the exact replacement, and give each significant notice an HTML context page. Keep protected PDFs behind authorisation.

| Observed old path | Proposed destination | Intent / acceptance |
|---|---|---|
| / and /home | / | Root returns 200; /home redirects once to root. |
| /book/5 | /books/tac-examination-book | Book purchase intent. Server 301 to a 200 product page; price and edition match catalogue. |
| /imp_downloads | /notices-and-downloads | Browse notices, results and documents. Retain legacy PDF targets and source attribution. |
| /pib_question_papers | /preparation/previous-papers | Past exam questions. Distinct task and title from practice sets. |
| /practice_test_papers | /preparation/practice-papers | Practice test intent. Group by subject or year only where real content exists. |
| /ta_poilicies and /page/{id} | /policies and mapped content slugs | Build individual redirects only after confirming the matching content; avoid blanket homepage redirects. |
| /login and /buy_now/5 | Preserve or map to tested functional equivalents | Utility routes excluded from sitemap; preserve buyer access and bookmarks. Never redirect a payment POST using content-page rules. |

**P1 • Content and internal linking.** Results visitors should land on a dated notice HTML page and reach the attributed source and alert signup. Officer/soldier guides should link to their matching alert form. A solved paper should link to its exam guide, adjacent years and the book only when relevant. Book-intent users should land on the product page. Separate publisher merchandise from recruitment actions. Example title/H1 pair: “TAC Examination Book | Edition, Price & Delivery” / “Territorial Army Commission Examination Book”. Treat query ideas as hypotheses until Search Console data arrives.

**P1 • Structured data and index controls.** Generate BreadcrumbList for hierarchical public pages and Product/Offer for the book, with visible INR price, applicable availability, image and canonical URL. Use review data only when genuine, visible, eligible and approved. Validate using Google's Rich Results Test. A passing test does not guarantee a search feature. XML sitemap contains only published, indexable, canonical 200 URLs and meaningful lastmod. Avoid indexable combinations of filter and sort parameters. Use noindex on utility HTML when appropriate; keep noindex crawlable. Authenticate admin and gated downloads, protect staging with authentication, and ensure production launch removes staging restrictions only from public content. Confirm live robots.txt and sitemap responses during the inventory.

**P1 • Performance and verification.** Target field p75 LCP ≤2.5 s, INP ≤200 ms and CLS ≤0.1 separately for mobile and desktop. Run three consistent mobile Lighthouse lab runs per key template, record median, payload and LCP element. Use lab diagnostics until enough field traffic exists. On staging, crawl the complete public URL inventory and compare source and rendered HTML, status, redirect hops, canonical, robots, title, description, H1, crawl depth, image alt, broken links and structured data. Pass: no redirect chains in mapped URLs, no broken internal links on the crawl, no private records in public HTML/schema/sitemaps and all indexable templates pass metadata checks.

**P2 • Measurement.** SEO lead records the 28-day pre-launch baseline and previous comparable period; compare year-on-year for seasonal exam dates where data exists. Segment by India, device, landing page, and notice, preparation or book intent. Monitor clicks, impressions, CTR, indexed canonical pages, Google-selected canonical, 404s, soft 404s and Core Web Vitals. Track notice source clicks, PDF download clicks, verified alert registrations, checkout starts and verified paid purchases with non-identifying event labels. Calculate organic book conversion as paid purchases from organic sessions / organic sessions landing on book pages, with a documented attribution window. Check errors on launch day and days 7 and 28. Investigate page/query impression loss against indexing and redirects before changing copy. Search Console ownership, analytics, crawl logs, field vitals, traffic and keyword volumes were not available for the baseline.

## Phase prompts for Claude Code

### Phase 1 • Week 1 • Foundation and visual prototype

> Inspect the repo, initialise the proposed stack if needed and resolve compatible versions. Create the schema outline, permissions matrix and route inventory. Implement a real responsive Blade homepage with a featured photo, four task links, dated notices and a book feature using clearly synthetic fixtures. Include a notice detail and book template to validate the system. Deliver local setup, screenshots at 390 and 1440 px, evidence of keyboard navigation and the six-route SEO baseline. Record unresolved assets or credentials and proceed with placeholders where safe.

### Phase 2 • Week 2 • Publishing and public resources

> Implement Pages, hero media, Newsline & Reviews, PDFs, Study Material, Books and Exam Names. Build actual draft/preview/publish/withdraw flows and review moderation. Connect publication state to public HTML, menus, metadata, cache and sitemap. Implement resource categories and separate public and private storage. Demonstrate that publishing a synthetic notice updates the homepage and detail page; withdrawal clears both and the sitemap; a draft and a private file are inaccessible to an anonymous visitor.

### Phase 3 • Week 3 • Alerts and result registration

> Implement the two subscriber modules and Result Registration. Add expiring verification, separate audience consent, resend limits, unsubscribe, queued newsletter preview/test, list filters and authorised Excel export. Collect only the specified result fields. Re-submit a signup; prove deduplication, audience-specific unsubscribe and suppression at send time. Demonstrate an exam-filtered export and an access denial for an unauthorised editor. Assert the removed features are absent across routes, form validation and navigation.

### Phase 4 • Week 4 • Checkout, payment and buyer access

> Implement Orders, mobile checkout and CCAvenue integration behind a provider adapter. Use local simulated responses for CI and merchant sandbox for verified integration. Test tampered amount, failed, cancelled and delayed attempts, repeated or out-of-order responses, expired reset links and protected file entitlement. Demonstrate one paid order, one receipt and one access grant for a repeated success. Preserve prefixed legacy IDs. Log merchant sandbox checks still pending if access is unavailable. Deliver a redacted reconciliation runbook and test matrix.

### Phase 5 • Week 5 • Migration and SEO rehearsal

> Build idempotent import commands with dry-run summaries and a per-URL redirect map. Rehearse twice using approved test data. Compare counts, paid totals, suppressions, files and access grants. Crawl staging, validate canonical and indexability rules, product and breadcrumb markup, screenshot diffs, mobile performance and keyboard/axe results. Export a prioritised SEO audit CSV with URL, evidence, severity, owner, fix and retest result. List all unmatched URLs and migration exceptions for review.

### Phase 6 • Week 6 • Release candidate and handover

> Build from a clean checkout; rerun feature, browser, access, SEO and pricing checks. Rehearse backup restore, the final delta import, worker restart, callback verification, mail delivery and rollback on staging. Provide the release checklist, verified environment requirements, staff walkthrough and a tracking sheet for launch day, day 7 and day 28. Flag owner decisions and any unverified merchant sandbox, mail or analytics step. Deliver the release candidate; the site owner controls the production switch.

## Definition of done

A notice publishes visibly; a visitor can find a current result and download its source; separate alert lists verify and suppress correctly; result records export by exam; a test book purchase gives precisely one authorised buyer entitlement; protected files and passwords never appear publicly; the eleven admin modules honour role policies and exclusions. The migrated route inventory has a disposition for each URL. Visual, accessibility, SEO, restore, payment and migration tests have recorded outcomes. Mark work blocked or simulated where evidence is missing. Existing proposal provides for source handover, 1–2 hours of staff training and 30 days of bug fixes.

## Reference links

- Laravel release support and PHP compatibility — https://laravel.com/docs/13.x/releases
- Filament 5 installation requirements — https://filamentphp.com/docs/5.x/introduction/installation
- Google site migration and redirects — https://developers.google.com/search/docs/crawling-indexing/site-move-with-url-changes
- Google product structured data — https://developers.google.com/search/docs/appearance/structured-data/product-snippet
- Core Web Vitals definitions and thresholds — https://web.dev/articles/vitals
- Live reference and public-template review — https://jointerritorialarmy.com/
