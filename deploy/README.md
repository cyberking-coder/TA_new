# Deploy reference (Phase 6)

These are reference templates. The owner's ops team adapts them to the final Hostinger VPS hostname + paths; nothing here auto-applies.

- `scripts/rebuild-clean.sh` — nukes vendor/node_modules/sqlite, reinstalls, migrates, seeds, tests, release-health. Run on a disposable worktree to prove the repo boots from scratch.
- `scripts/restore-rehearse.sh` — backup → mutate → restore cycle; asserts the mutation was reverted. Non-destructive to committed data.
- `scripts/deploy.sh` — pull → install → cache → migrate → reload PHP-FPM → restart queue workers → post-deploy health. Rerunnable.
- `nginx/ta.conf` — vhost. Replace `<DOMAIN>`. Staging section is commented — enable it until go-live.
- `supervisor/ta-worker.conf` — two queue workers.
- `systemd/ta-scheduler.service` + `.timer` — minutely `schedule:run`.

Owner inputs still needed before any of this is wired on a real host:
- Hostinger VPS hostname + IP + SSH key for the `ta` deploy user.
- CCAvenue integration kit (merchant ID, access code, working key, test card).
- SES or Resend decision + verified sending domain.
- Cloudflare R2 bucket names + access key for `public` / `private` disks.
- Google Search Console verification token + Analytics property ID.
