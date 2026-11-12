# Permissions matrix

Three roles per PRD. MFA mandatory for all staff. Policies gate every private action — sidebar visibility is never the sole access control (PRD: "Test export and payment permissions independently of sidebar visibility").

Legend: ✅ allowed · 👁 view-only · ❌ denied

| Capability | administrator | content editor | operations |
|---|:-:|:-:|:-:|
| **Content** | | | |
| Pages — draft/preview/publish/withdraw | ✅ | ✅ | 👁 |
| Hero/slider — edit featured image | ✅ | ✅ | 👁 |
| Newsline & Reviews — moderate | ✅ | ✅ | 👁 |
| PDFs — upload (public) | ✅ | ✅ | 👁 |
| PDFs — upload (private, buyer-only) | ✅ | ✅ | ❌ |
| Study Material — manage | ✅ | ✅ | 👁 |
| **People & Exams** | | | |
| Exam Names — manage | ✅ | ✅ | 👁 |
| Result Registration — view | ✅ | ✅ | ✅ |
| Result Registration — export (Excel, per exam) | ✅ | ❌ | ✅ |
| Officer / Soldier Subscribers — view | ✅ | 👁 | ✅ |
| Subscribers — export | ✅ | ❌ | ✅ |
| Send newsletter (preview + test + send) | ✅ | ❌ | ✅ |
| **Commerce** | | | |
| Books / editions — manage | ✅ | ✅ | 👁 |
| Orders — list & search | ✅ | ❌ | ✅ |
| Orders — open shipping / contact detail | ✅ | ❌ | ✅ |
| Orders — export | ✅ | ❌ | ✅ |
| Orders — change dispatch status | ✅ | ❌ | ✅ |
| Payment — manual reconciliation action | ✅ | ❌ | ✅ |
| **Access control** | | | |
| Buyer access — view entitlements | ✅ | ❌ | ✅ |
| "Open to all" study toggle (if retained) | ✅ | ❌ | ❌ |
| Staff user management + MFA reset | ✅ | ❌ | ❌ |

## Hard rules enforced in policy layer

1. **No role can read buyer passwords.** Columns return hashes only; export queries strip the column at the model boundary.
2. **No role can access coupon management, consignment upload or exam centres.** These modules do not exist in the app.
3. **Operations cannot publish content, and content editors cannot see orders or exports.**
4. **Every export is audit-logged** with actor, filter and row count.
5. **Newsletter send re-checks suppression at send time**, not just at queue time.
6. **Spreadsheet-formula input** (`=`, `+`, `-`, `@` as first char) in subscriber-facing fields is neutralised on export.
