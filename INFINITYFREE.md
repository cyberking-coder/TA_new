# InfinityFree deploy (trial / demo only)

InfinityFree is a free shared PHP host. Good enough to show the public
frontend to the client. The admin panel (Filament) *might* work but hits
InfinityFree's memory ceiling; a real staging host (Render / Fly / Hetzner)
is still the right answer for day-to-day review.

## 1. Create the MySQL database

- Login → **Control Panel** → **MySQL Databases**.
- Create one. Note **database name** (`epiz_xxx_tafg`), **username**
  (`epiz_xxx`), **password**, **host** (`sql2xx.infinityfree.com`).

Important: InfinityFree only accepts MySQL connections from inside
their network — migrations have to run from inside a PHP request on
the server, not from your laptop.

## 2. Build the Laravel bundle locally

From Git Bash in `D:\TA_new`:

```sh
composer install --no-dev --optimize-autoloader --no-interaction
php artisan key:generate --show
```

Copy the `base64:…` string from `key:generate --show`.

Also fresh-run tests to be sure the bundle is green:

```sh
php artisan test
```

## 3. Produce the files you upload

You'll end up uploading two trees over FTP:

```
/home/vol_xxx/
    laravel/       ← the entire repo (every file EXCEPT `.git`, `tests/`,
                     `node_modules/`, `storage/logs/*`, `.github/`,
                     `data-import/`, `deploy.sh`, `render.yaml`,
                     `vercel.json`, `railway.json`, `Procfile`,
                     `.vercelignore`, `DEPLOY.md`, `README.md`)
    htdocs/        ← ONLY two files
        index.php  ← from this repo: htdocs-index.php (renamed)
        .htaccess  ← from this repo: htdocs.htaccess (renamed)
```

No file edits needed — `htdocs-index.php` already points at `../laravel`.

Also inside `laravel/`:

- Place a `.env` with the values from the main guide (APP_KEY from step 2,
  DB host/name/user/pass from step 1, APP_URL = your subdomain).
- `chmod 775` the following so Laravel can write to them:
  - `storage/`
  - `storage/framework/`
  - `storage/framework/cache/`
  - `storage/framework/sessions/`
  - `storage/framework/views/`
  - `storage/logs/`
  - `bootstrap/cache/`
- Delete any pre-existing `bootstrap/cache/*.php` so Laravel rebuilds them.

## 4. Upload

Use **FileZilla** (free) with InfinityFree's FTP credentials (Control Panel →
FTP Accounts). Big upload — budget 10–20 minutes for `vendor/`.

## 5. Run migrations (one-time URL)

Before the first page load, you need the schema in MySQL. Add this route
temporarily (we'll remove after) — I'll ship it as a dedicated artisan-via-
URL endpoint in the repo so you don't have to type it:

```
https://<your-subdomain>.infinityfreeapp.com/ops/migrate?token=<ONE_TIME_TOKEN>
```

The token is an env var you set in `.env` (`OPS_TOKEN`). After migrations
succeed, blank that env var or delete the route.

## 6. Create the admin user

Same mechanism — a one-time route that reads name/email/password from
query params, protected by `OPS_TOKEN`.

```
https://<your-subdomain>.infinityfreeapp.com/ops/seed-admin?token=<TOKEN>&email=you@domain.test&password=<pick>
```

## 7. Set `APP_DEBUG=false`

Once everything renders, flip `.env` locally, re-upload `.env`. Do NOT
leave `APP_DEBUG=true` on a public URL for longer than you have to.

## Known limitations

| Feature | Status on InfinityFree |
|---|---|
| Public frontend | Works |
| Filament admin panel | May hit memory limits on some pages |
| Mail (Resend) | Works via API |
| SMS (Fast2SMS) | Works via API |
| Cloudflare R2 uploads | Works via API |
| Queue worker (`queue:work`) | Not available — stay on `sync` |
| Scheduler (`schedule:run` cron) | No cron — scheduled tasks don't run |
| Long-running requests | 90s cap (set in `.htaccess` here) |
| Custom PHP extensions | Fixed list from InfinityFree |
| HTTPS | Only on their `*.infinityfreeapp.com` subdomain |

For client handover use the Hetzner path in `DEPLOY.md`; keep InfinityFree
for sharing a trial link only.
