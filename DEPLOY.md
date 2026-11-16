# Deployment guide

This project ships two supported paths. Pick the one your target host uses.

- **Railway** — zero-ops preview deploys while we iterate before client handover.
  (Vercel's community PHP runtime `vercel-php` is unreliable on current Vercel
  — see `vercel.json`/`.vercelignore` if you want to retry it later, but Railway
  is the recommended staging target.)
- **Hetzner (or any Linux VPS)** — the production home after handover.

Both paths read the same code; only the runtime and `.env` differ.

---

## Prerequisites

| Area        | What you need                                                                |
|-------------|------------------------------------------------------------------------------|
| PHP         | 8.3 or 8.4                                                                   |
| Composer    | 2.x                                                                          |
| Database    | MySQL 8 (production) — SQLite is dev-only                                    |
| Cache / queue | Redis 7 on Hetzner; `database` driver on Vercel                            |
| File storage  | Cloudflare R2 bucket (`territorial-army`) with an API token (R/W)          |
| Mail        | Resend account + verified sender domain                                      |
| SMS         | Fast2SMS account (transactional route)                                       |
| Captcha     | Google reCAPTCHA v2 site + secret keys                                       |
| Payments    | CCAvenue live merchant ID / access code / working key                        |
| DNS         | A/AAAA (Hetzner) **or** CNAME to `cname.vercel-dns.com` (Vercel)             |

Copy `.env.production.example` → `.env` on the server and fill every blank.
Generate `APP_KEY` with `php artisan key:generate --show` and paste the result.

---

## Path A — Railway (preview / staging)

Railway runs the app as a long-lived container (not serverless), so Laravel
behaves normally — queue workers, filesystem sessions, scheduled tasks all
work out of the box.

### Steps

1. Sign up at railway.com (GitHub login is fine). The $5/mo free trial
   covers a staging deployment indefinitely for a single container + DB.
2. **New Project → Deploy from GitHub repo** → pick `cyberking-coder/TA_new`.
3. On the project canvas click **+ New → Database → Add PostgreSQL**.
   Railway spins up a managed Postgres instance beside the app.
4. Click your app service → **Variables** → paste every value from
   `.env.production.example`. For DB, use the `${{Postgres.DATABASE_URL}}`
   reference that Railway shows (set it as `DB_URL`) OR map individual
   fields:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=${{Postgres.PGHOST}}
   DB_PORT=${{Postgres.PGPORT}}
   DB_DATABASE=${{Postgres.PGDATABASE}}
   DB_USERNAME=${{Postgres.PGUSER}}
   DB_PASSWORD=${{Postgres.PGPASSWORD}}
   DB_SSLMODE=require
   ```
   Railway's `${{...}}` syntax auto-wires the two services.
5. Set the app's **Public Domain** under **Settings → Networking → Generate
   Domain**. Copy the generated URL into `APP_URL`.
6. The first deploy runs automatically on push. Nixpacks reads
   `nixpacks.toml` from the repo: installs PHP 8.3 + extensions + composer
   deps (`--no-dev`), caches config/routes/events, links storage.
7. The start command (also in `nixpacks.toml`) runs
   `php artisan migrate --force` on every container boot, then
   `artisan serve`. First boot creates all tables.
8. Open the generated URL in a browser. Admin seeding:
   ```sh
   railway run php artisan tinker --execute="App\Models\User::create(['name'=>'Admin','email'=>'you@domain','password'=>bcrypt('<pick>'),'role'=>'administrator','email_verified_at'=>now()]);"
   ```
   (Install the Railway CLI with `npm i -g @railway/cli` first; `railway link`
   the project.)
9. Hit `https://<domain>/health` — must return `{"status":"ok", ...}`.

### Supabase vs Railway Postgres

Railway's managed Postgres is simpler for staging (auto-wired env vars,
no pooler to remember). Supabase gives you the dashboard, Auth, Storage
and realtime that we don't need here. Either works — pick one, don't run
both. If you already set up Supabase, keep using it and skip step 3; use
the Supabase pooler env vars as covered in Path B's notes.

### Legacy: Vercel (why we moved off it)

A Vercel config (`vercel.json`, `api/index.php`, `.vercelignore`) is still
in the repo in case Vercel ever re-adds supported PHP runtime. Current
status (Nov 2026): the community `vercel-php@0.7.3` builder consistently
fails with `ERR_MODULE_NOT_FOUND: launcher.launcher`. Don't use Vercel
for this project until that's fixed upstream.

## Path A1 — original Vercel notes (kept for reference)

Vercel runs each request as a short-lived serverless function. That means:

- **No persistent filesystem.** All user uploads must go to R2 (`FILESYSTEM_DISK=r2`).
- **No long-running worker.** `QUEUE_CONNECTION=sync` or external queue (Upstash).
- **No `php artisan serve`.** Vercel calls `api/index.php`, which forwards into
  `public/index.php`. `vercel.json` already wires this up.
- **Scheduled tasks.** Use Vercel Cron Jobs or an external cron (hit
  `/up` or a `/cron/*` route).

### Steps

1. Create a **Supabase** project (free tier is fine for staging). Pick the
   region closest to your Vercel region (e.g. `ap-south-1` for India users
   on Vercel Singapore). Grab the connection details from
   *Project Settings → Database → Connection pooling*:
   - Use the **Transaction pooler** on port **6543**, not the direct `5432`.
     Vercel's short-lived PHP functions must not hold persistent connections,
     and the direct port runs out of slots fast.
   - Set `DB_SSLMODE=require`.
   - The username is `postgres.<project-ref>`, the database is `postgres`.
   (Alternatives that also work: PlanetScale / Neon / Aiven.)
2. In the Vercel dashboard, set every variable from `.env.production.example`
   as a Project Environment Variable.
3. Import the repo in Vercel. Use the included `vercel.json`. No framework
   preset — it's custom.
4. First deploy: Vercel runs `composer install --no-dev` and caches config
   via the `buildCommand` in `vercel.json`.
5. Run migrations once from your laptop against the Vercel-reachable DB.
   Supabase example:
   ```sh
   DB_CONNECTION=pgsql \
   DB_HOST=aws-0-ap-south-1.pooler.supabase.com \
   DB_PORT=6543 \
   DB_DATABASE=postgres \
   DB_USERNAME=postgres.<project-ref> \
   DB_PASSWORD=<supabase-db-password> \
   DB_SSLMODE=require \
   php artisan migrate --force
   ```
6. Seed the first admin:
   ```sh
   php artisan tinker --execute="App\Models\User::create(['name'=>'Admin','email'=>'you@domain','password'=>bcrypt('<pick>'),'role'=>'administrator','email_verified_at'=>now()]);"
   ```
7. Hit `https://<domain>/health` — must return `{"status":"ok", ...}`.

### Vercel limits to know

- 10 s Hobby / 60 s Pro function timeout — large legacy-order imports should
  run from your laptop against prod DB, not from a web request.
- Function cold start ≈ 200–400 ms for Laravel. Expect it on low traffic.
- 4.5 MB request body cap — admin PDF uploads route through R2 directly
  (Livewire temp-upload disk stays `local` only in dev).

---

## Path B — Hetzner / VPS (handover target)

### One-time server setup

Ubuntu 24.04 LTS.

```sh
# PHP 8.4, nginx, redis, mysql
sudo apt update
sudo apt install -y nginx redis-server mysql-server certbot python3-certbot-nginx \
    php8.4-fpm php8.4-cli php8.4-mysql php8.4-redis php8.4-mbstring \
    php8.4-xml php8.4-curl php8.4-zip php8.4-gd php8.4-intl php8.4-bcmath
curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer

# App user
sudo adduser --disabled-password --gecos "" deploy
sudo usermod -aG www-data deploy
```

Create database:

```sql
CREATE DATABASE tafieldguide CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'tafieldguide'@'localhost' IDENTIFIED BY '<pick>';
GRANT ALL ON tafieldguide.* TO 'tafieldguide'@'localhost';
```

Clone the repo:

```sh
sudo -iu deploy
git clone https://github.com/<owner>/TA_new.git ~/ta-field-guide
cd ~/ta-field-guide
cp .env.production.example .env  # then edit
php artisan key:generate
```

### Nginx server block

`/etc/nginx/sites-available/jointerritorialarmy.com`:

```nginx
server {
    listen 80;
    server_name jointerritorialarmy.com www.jointerritorialarmy.com;
    return 301 https://$host$request_uri;
}
server {
    listen 443 ssl http2;
    server_name jointerritorialarmy.com www.jointerritorialarmy.com;
    root /home/deploy/ta-field-guide/public;
    index index.php;

    client_max_body_size 20M;

    ssl_certificate     /etc/letsencrypt/live/jointerritorialarmy.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/jointerritorialarmy.com/privkey.pem;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    }
    location ~ /\.(?!well-known).* { deny all; }
    location ~* \.(?:css|js|woff2?|svg|webp|jpg|png)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

```sh
sudo ln -s /etc/nginx/sites-available/jointerritorialarmy.com /etc/nginx/sites-enabled/
sudo certbot --nginx -d jointerritorialarmy.com -d www.jointerritorialarmy.com
sudo systemctl reload nginx
```

### Queue worker (systemd)

`/etc/systemd/system/ta-queue.service`:

```ini
[Unit]
Description=TA Field Guide queue worker
After=network.target

[Service]
User=deploy
Group=www-data
WorkingDirectory=/home/deploy/ta-field-guide
ExecStart=/usr/bin/php artisan queue:work redis --tries=3 --backoff=30 --max-time=3600 --sleep=2
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

```sh
sudo systemctl daemon-reload
sudo systemctl enable --now ta-queue
```

### Scheduler (cron)

```cron
* * * * * cd /home/deploy/ta-field-guide && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

### Every release

From your laptop, push to `main`, then on the server:

```sh
sudo -iu deploy
cd ~/ta-field-guide
./deploy.sh
```

`deploy.sh` pulls `main`, installs `--no-dev`, migrates, re-caches, restarts
the worker, and brings maintenance mode down.

---

## Post-deploy checklist

- [ ] `curl https://<domain>/health` → `{"status":"ok","db":"ok","cache":"ok"}`
- [ ] `curl -I https://<domain>/` → `strict-transport-security`, `x-frame-options`, `x-content-type-options`, `referrer-policy` headers present
- [ ] `/robots.txt` lists the sitemap; `/sitemap.xml` returns XML
- [ ] Admin can log in at `/admin`; first login's password is already the one
      you set in step 6/`tinker`
- [ ] Hero slides upload → appear with crossfade
- [ ] Alerts OTP sends real SMS (test from your own number)
- [ ] CCAvenue test transaction succeeds end-to-end
- [ ] Mail: Resend dashboard shows the test welcome email was delivered
- [ ] Sentry (if enabled) receives a deliberate test exception

## Rollback

```sh
sudo -iu deploy
cd ~/ta-field-guide
git log --oneline -5
git reset --hard <previous-sha>
./deploy.sh
```

If the regression was a migration, add a `down()` or a follow-up migration;
`migrate:rollback` in production is only safe when you understand the exact
blast radius.
