# Route inventory

Snapshot of the routes the rebuild commits to. Grouped by audience. Legacy → new mapping lives in `docs/legacy-redirects.md`.

## Public (Blade, server-rendered)

| Method | Path | Name | Purpose | Phase |
|---|---|---|---|---|
| GET | `/` | `home` | Hero + 4 task tiles + latest 3 notices + book feature. | 1 |
| GET | `/notices-and-downloads` | `notices.index` | Dated list, exam/year/type filters, reset. | 1 → 2 |
| GET | `/notices/{slug}` | `notices.show` | Notice HTML detail with source attribution + PDF link. | 1 |
| GET | `/preparation/previous-papers` | `prep.previous` | Solved papers (subject/year grouping). | 1 → 2 |
| GET | `/preparation/practice-papers` | `prep.practice` | Practice sets, distinct from solved. | 2 |
| GET | `/books/{slug}` | `books.show` | Product page: cover, edition, INR price, Buy. | 1 |
| GET | `/checkout/{book:slug}` | `checkout.show` | Single-column mobile checkout. | 4 |
| POST | `/checkout/{book:slug}` | `checkout.submit` | Server prices + creates pending order + payment attempt. | 4 |
| GET | `/payment/return` | `payment.return` | Browser return from gateway (display only, not source of truth). | 4 |
| POST | `/payment/callback` | `payment.callback` | Server-to-server merchant response verification. | 4 |
| GET | `/alerts/officer` | `alerts.officer` | Officer subscribe / result registration form. | 3 |
| GET | `/alerts/soldier` | `alerts.soldier` | Soldier subscribe / result registration form. | 3 |
| POST | `/alerts/subscribe` | `alerts.subscribe` | Email verification send. | 3 |
| GET | `/alerts/verify/{token}` | `alerts.verify` | One-shot verification. | 3 |
| POST | `/alerts/unsubscribe` | `alerts.unsubscribe` | List-specific suppression. | 3 |
| GET | `/study/login` | `study.login` | Buyer login (order ID + password). | 4 |
| POST | `/study/login` | `study.authenticate` | Buyer auth. | 4 |
| GET | `/study/reset` | `study.reset` | Expiring single-use reset link request. | 4 |
| GET | `/study/materials` | `study.index` | Entitled library. | 4 |
| GET | `/study/download/{material}` | `study.download` | Signed short-lived URL to private file. | 4 |
| GET | `/policies/{slug}` | `policies.show` | Policy pages (privacy, terms, refund). | 2 |
| GET | `/sitemap.xml` | `sitemap` | Only published, indexable, canonical 200 URLs. | 2 |
| GET | `/robots.txt` | `robots` | Live robots delivered from app. | 1 |

## Admin (Filament 5, `/admin`)

Grouped per PRD into Content, People & Exams, Commerce.

**Content:** Pages, Slider/Hero, Newsline & Reviews, PDFs, Study Material
**People & Exams:** Exam Names, Result Registration, Officer Subscribers, Soldier Subscribers
**Commerce:** Books, Orders

All admin routes sit behind staff guard + MFA + Laravel policies. Phase 2 lands content modules; phase 3 adds subscribers/results; phase 4 adds orders.

## Legacy redirects (P0 migration)

| Legacy | New | Hop |
|---|---|---|
| `/home` | `/` | 301 |
| `/book/5` | `/books/tac-examination-book` | 301 |
| `/imp_downloads` | `/notices-and-downloads` | 301 |
| `/pib_question_papers` | `/preparation/previous-papers` | 301 |
| `/practice_test_papers` | `/preparation/practice-papers` | 301 |
| `/ta_poilicies` | per-slug `/policies/{slug}` | 301 (individual) |
| `/page/{id}` | mapped content slug | 301 (individual) |
| `/login` | `/study/login` | 301 |
| `/buy_now/5` | `/checkout/tac-examination-book` | 301, GET only |

Payment POSTs are **never** redirected through content rules.

## Excluded (do not add routes, menus, fields, imports)

- Exam Centres
- Upload Consignment CSV
- Coupons (checkout must have no coupon-code field)
- "Move to 100% Officers" operation
