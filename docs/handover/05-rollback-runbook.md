# Rollback runbook

Triggered when the launch-day checklist's "Rollback trigger" fires or ops judges the site cannot stay up.

## Decision flowchart

1. **Is the problem server-only?** (5xx but DB intact.) → Re-deploy the previous commit.
2. **Is the problem data-corrupting?** (Writes going to wrong rows, duplicate charges.) → Pause checkout, reconcile, consider DB restore.
3. **Is the problem payment-only?** (Checkout 500s, callback bouncing.) → Pause checkout only; leave content live.
4. **Is the problem catastrophic?** (Domain unreachable, prolonged 5xx.) → DNS rollback to the legacy host.

## 1 — Re-deploy previous commit

```bash
# On the Hostinger VPS, as the ta user:
cd /var/www/ta
git log --oneline -5                                # confirm the known-good SHA
git reset --hard <previous-SHA>
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force                         # safe: migrations are forward-only; no down() needed
php artisan config:cache
sudo /usr/sbin/service php8.4-fpm reload
sudo /usr/bin/supervisorctl restart ta-worker:*
php artisan release:health --base="https://<domain>"
```

## 2 — Pause checkout only

Fastest and reversible. Keeps content, alerts, notices live.

```bash
# Option A: flip to the simulator (checkout still renders but no real charge).
echo "PAYMENT_PROVIDER=simulator" >> /etc/ta/env
sudo /usr/sbin/service php8.4-fpm reload

# Option B: 503 the checkout route via nginx snippet.
# Add inside server {} block in nginx/ta.conf:
#   location ~ ^/(checkout|payment)/ { return 503 "Checkout paused for maintenance"; }
sudo nginx -t && sudo systemctl reload nginx
```

Then reconcile per `docs/phase-4-report.md` — pay particular attention to `payment_attempts` with `status=unknown` and orders where the browser return landed but no server-to-server callback followed.

## 3 — Restore the DB from a backup

```bash
# List candidates (most recent first)
ls -lrt /var/backups/ta/ | tail -5

# Take a safety snapshot before restoring
php artisan backup:db --out=/var/backups/ta/pre-restore-$(date +%Y%m%d-%H%M).sql.gz

# Restore
php artisan restore:db /var/backups/ta/db-<timestamp>.sql.gz --force

# Re-cache + reload
php artisan config:cache
sudo /usr/sbin/service php8.4-fpm reload
sudo /usr/bin/supervisorctl restart ta-worker:*

# Health check
php artisan release:health --base="https://<domain>"
```

**Caveat:** any orders placed between the backup timestamp and now are lost from the new site but may still exist at CCAvenue. After restore, immediately:

1. Pause checkout (see step 2).
2. Pull the gap window from the CCAvenue dashboard.
3. For each gap transaction, email the buyer, refund the gateway charge, and ask them to re-place the order.

Never attempt to retrofit missing orders by writing directly to the DB.

## 4 — DNS rollback to legacy host

Last resort. Owner-controlled.

1. In the DNS provider, change the A/AAAA records for `<domain>` back to the legacy host's IP.
2. TTL has been pre-lowered to 300 s per the T-1 checklist; propagation is ~5–15 min in practice.
3. Email buyers: "The site is temporarily unavailable; existing purchases are safe; new purchases will be enabled shortly."
4. Diagnose the new-site failure in parallel. Do not cut over again until the clean-rebuild script passes.

## Who approves what

| Action | Required approver |
|---|---|
| Re-deploy previous commit | On-call developer |
| Pause checkout (simulator flip) | Ops lead |
| DB restore | Owner + ops lead (both must agree, in writing) |
| DNS rollback | Owner |

## After any rollback

- Open an incident note in the owner's issue tracker with: trigger, actions taken, timestamps, blast radius (how many users affected), root cause, prevention.
- Add a test that would have caught the problem.
- Update `docs/build-status.md` with the regression note so the next session reads it.
