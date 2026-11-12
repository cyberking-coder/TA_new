#!/usr/bin/env bash
# Phase 6 production deploy. Runs on the Hostinger VPS as the deploy user.
# Idempotent: safe to rerun. Does NOT touch the DB except migrations.
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/ta}"
cd "$APP_DIR"

echo "== take pre-deploy DB backup =="
php artisan backup:db

echo "== fetch =="
git fetch origin
git reset --hard origin/main   # <-- confirm the owner wants auto-fast-forward here

echo "== install =="
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
npm ci
npm run build

echo "== cache =="
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "== migrate =="
php artisan migrate --force

echo "== restart services =="
# Reload PHP-FPM (zero-downtime)
sudo /usr/sbin/service php8.4-fpm reload
# Restart the queue workers so they pick up the new code
sudo /usr/bin/supervisorctl restart ta-worker:*

echo "== post-deploy health =="
php artisan release:health --base="https://$(cat /etc/hostname)"

echo ""
echo "Deploy complete. Verify /payment/callback is reachable from the merchant IPs."
