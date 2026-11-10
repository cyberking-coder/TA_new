# Release checklist

Phase 6 release-candidate checklist. The site owner controls the production switch; nothing below auto-applies.

## T-3 days

- [ ] Final feature test pass against staging (`php artisan test` returns 31/31).
- [ ] Clean-rebuild script runs end-to-end on a fresh worktree (`deploy/scripts/rebuild-clean.sh`).
- [ ] Restore rehearsal passes (`deploy/scripts/restore-rehearse.sh`).
- [ ] `php artisan seo:audit` run on staging, P0 findings = 0 on the four priority templates.
- [ ] `php artisan seo:redirect-map` CSV has no `MISSING in routes!` entries.
- [ ] Owner has supplied: licensed hero + book cover artwork, four task pictograms, licence records. Placeholders removed.
- [ ] Owner has supplied: authorised offline legacy extract. First rehearsal against real data run; reconciled per `docs/phase-5-report.md`.
- [ ] Owner has supplied: CCAvenue test credentials + integration kit. `PAYMENT_PROVIDER=ccavenue` smoke in sandbox completed.
- [ ] Mail transport chosen (SES vs Resend) + sending domain DNS verified (SPF, DKIM, DMARC).
- [ ] Cloudflare R2 buckets provisioned; `public` and `private` disk env vars configured.
- [ ] MFA enrolment completed for every staff account.

## T-1 day

- [ ] Backup taken from staging (`php artisan backup:db`); off-host copy stored.
- [ ] Hostinger VPS provisioned per `docs/handover/02-environment-requirements.md`.
- [ ] Nginx config installed, TLS valid, HSTS header present.
- [ ] Supervisor workers running (2 processes per `deploy/supervisor/ta-worker.conf`).
- [ ] Systemd scheduler timer enabled.
- [ ] Staging URL behind HTTP basic auth; `robots.txt` serves disallow-all.
- [ ] `php artisan release:health --base=https://staging.<domain>` returns all ✓.
- [ ] Staff walkthrough delivered (1-2h per PRD scope).

## Launch day (T-0)

- [ ] Final backup taken on the legacy site (owner's responsibility).
- [ ] Final delta import run on staging, counts + paid totals reconciled.
- [ ] Final backup taken on the new site (`php artisan backup:db`).
- [ ] DNS cutover: point `<domain>` A/AAAA records at the new host.
- [ ] HTTP basic auth removed from the public vhost (staging exclusions lifted).
- [ ] `robots.txt` switched to production (`APP_ENV=production`).
- [ ] Sitemap submitted in Google Search Console.
- [ ] One test checkout taken against the real merchant sandbox (see day-7 tracker).
- [ ] Monitor `/payment/callback` receives server-to-server POSTs from CCAvenue IPs.
- [ ] Monitor `storage/logs/laravel.log` for first hour; look for 5xx and Mail queue errors.

## T+1 day

- [ ] Review Google Search Console coverage report.
- [ ] Verify the seven legacy URLs still 301 correctly.
- [ ] Check first-day analytics (no PII in event payloads).
- [ ] Confirm backups have run on the new host.

## T+7 and T+28

See `docs/handover/04-tracking-sheet.csv`. Fill in after each checkpoint; the PRD-required measurement cadence.

## Rollback trigger

If `/payment/callback` fails after cutover OR >1 % of checkouts return 5xx:

1. Pause checkout by switching `PAYMENT_PROVIDER=simulator` (shows "payment is being confirmed" instead of charging).
2. Reconcile pending payments per `docs/phase-4-report.md` runbook.
3. Decide whether to proceed or roll back DNS to the legacy host.
4. See `docs/handover/05-rollback-runbook.md`.
