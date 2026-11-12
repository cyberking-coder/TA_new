# Environment requirements

Target: Hostinger VPS, Ubuntu 24.04 LTS or Debian 12.

## Runtime (verified against the repo's lockfiles)

| Component | Version | Verified by |
|---|---|---|
| PHP (CLI + FPM) | 8.3–8.5 | `composer.json`: `"php": "^8.3"`; container tested on 8.3.6 |
| Composer | 2.x | Resolves `composer.lock` without platform warnings |
| Node.js | 22.x | `npm run build` succeeds with Node 22.23 |
| npm | 10.x | Container tested |
| MySQL | 8.4 | Target; dev uses SQLite |
| Nginx | 1.24+ | Templates in `deploy/nginx/` |
| Supervisor | 4.x | Templates in `deploy/supervisor/` |
| systemd | — | Scheduler via `deploy/systemd/` |

## PHP extensions required

`bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `gd` (or `imagick`), `intl`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `session`, `tokenizer`, `xml`, `zip`, `sodium`.

```bash
sudo apt install -y php8.4-cli php8.4-fpm php8.4-mysql php8.4-mbstring \
  php8.4-xml php8.4-curl php8.4-zip php8.4-intl php8.4-bcmath php8.4-gd
```

## Filesystem layout

- App root: `/var/www/ta`
- Writable: `storage/` and `bootstrap/cache/` owned by the `ta` user
- Backups: `/var/backups/ta/` with off-host nightly copy (rsync/rclone)

## Environment variables (production)

These MUST be set before `php artisan config:cache`. Use the Hostinger VPS secrets store, not committed env files.

```env
APP_NAME="Territorial Army exam guide"
APP_ENV=production
APP_KEY=<generated>
APP_DEBUG=false
APP_URL=https://<domain>

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ta
DB_USERNAME=ta
DB_PASSWORD=<supplied-by-owner>

QUEUE_CONNECTION=database     # or redis once provisioned
CACHE_STORE=file

SESSION_DRIVER=database
SESSION_LIFETIME=120

MAIL_MAILER=resend            # or ses
MAIL_FROM_ADDRESS=alerts@<domain>
MAIL_FROM_NAME="Territorial Army exam guide"
RESEND_KEY=re_XXXXXXXXXXXXX   # see docs/handover/07-resend-setup.md
# Alternative: SES via the AWS SDK (still supported)
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=ap-south-1

# Cloudflare R2 via s3 driver
FILESYSTEM_DISK=s3
AWS_BUCKET=ta-public
AWS_ENDPOINT=https://<account>.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=true

PAYMENT_PROVIDER=ccavenue
PAYMENT_SIMULATOR_SECRET=<rotate-not-used-in-prod>
CCAVENUE_MERCHANT_ID=<supplied-by-owner>
CCAVENUE_ACCESS_CODE=<supplied-by-owner>
CCAVENUE_WORKING_KEY=<supplied-by-owner>
CCAVENUE_ACTION_URL=https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction

GOOGLE_ANALYTICS_ID=<supplied-by-owner>
```

## Outbound network

The following must be reachable from the VPS:
- CCAvenue merchant endpoints (production + any status API)
- SES or Resend SMTP endpoint
- Cloudflare R2 regional endpoint
- Let's Encrypt ACME endpoints for TLS renewal
- Google (sitemap pings)

## Inbound network

- 80 (HTTP → forced redirect to 443)
- 443 (HTTPS)
- 22 (SSH, source-restricted to the owner's admin IP range)
- CCAvenue callback POSTs to `/payment/callback` must be allowed from the merchant's publishing IP range (owner to supply).

## Verified against this repo

| Check | How |
|---|---|
| Composer resolves on target PHP | `composer check-platform-reqs` |
| Empty DB migrates + seeds | `php artisan migrate && php artisan db:seed --class=Phase2DemoSeeder` |
| Clean rebuild succeeds | `deploy/scripts/rebuild-clean.sh` |
| Backup/restore round-trips | `deploy/scripts/restore-rehearse.sh` |
| Public cache rules exclude private routes | `nginx/ta.conf` `location ~ ^/(admin|study|checkout|payment)/` |
