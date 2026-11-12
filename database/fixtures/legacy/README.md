# Synthetic legacy fixtures

**All rows are synthetic.** Never replace these with production data in the repo.
The real legacy extract is an authorised offline file supplied by the owner;
Phase 5 rehearsal points the importers at that file via `--source=...`.

Column conventions mirror what the field review found on the legacy site:
- Order refs preserve their prefix (`LEG-0000123`).
- Monetary values are integer paise.
- `0000-00-00` dates and `0000-00-00 00:00:00` timestamps are the MySQL
  zero-date the legacy site stored on unknown dates; the importer flips
  `import_date_unknown = true` and stores `NULL`.
- Duplicate emails inside `officer_subscribers.csv` prove dedupe-within-audience.
- `LEG-9999999` tests legacy IDs with non-fixed-width digits.
