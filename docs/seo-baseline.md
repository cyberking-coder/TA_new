# SEO baseline — six public routes

Baseline recorded from the PRD's measured inspection on 2026-11-10. Browser inspection of the rendered document only; no claims about index coverage, Google-selected canonicals or traffic. Image counts include shared layout assets.

| Path | Title | Meta description | H1 count | HTML canonical | JSON-LD blocks | Images missing alt / total |
|---|---|---|---:|---|---:|---:|
| `/` | empty | absent/empty | 0 | none | 0 | 14 / 14 |
| `/home` | empty | absent/empty | 0 | none | 0 | 14 / 14 |
| `/book/5` | empty | absent/empty | 0 | none | 0 | 7 / 7 |
| `/imp_downloads` | empty | absent/empty | 0 | none | 0 | 4 / 4 |
| `/pib_question_papers` | empty | absent/empty | 0 | none | 0 | 4 / 4 |
| `/practice_test_papers` | empty | absent/empty | 0 | none | 0 | 4 / 4 |

**47 image occurrences across the sample, all missing `alt`.** Zero JSON-LD on any sampled URL. Zero descriptive H1 on any sampled URL.

## Phase 1 commitments on rebuild templates

- Every rebuild template emits:
  - Nonempty, unique `<title>` tied to the content.
  - Meta description populated from the content's seo_description (fallback to summary).
  - Exactly one descriptive `<h1>`.
  - Absolute `<link rel="canonical">`.
  - Alt text on every informative image; `alt=""` on decorative imagery.
- Homepage, notice detail and book product page carry the correct JSON-LD (BreadcrumbList everywhere hierarchical; Product/Offer on the book) — landing in full in Phase 2/5, scaffolded in Phase 1.
- Links use descriptive text — no "Click Here".

## Rehearsal plan

Phase 5 rerun: crawl staging, compare source vs rendered HTML, status, redirect hops, canonical, robots, title, description, H1, crawl depth, image alt, broken links, structured data. Export prioritised CSV with URL / evidence / severity / owner / fix / retest result.
