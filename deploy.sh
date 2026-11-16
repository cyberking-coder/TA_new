#!/usr/bin/env bash
# =============================================================================
# Hetzner / bare-metal deploy script for Territorial Army Field Guide.
# Run on the server as the app user (e.g. `deploy`) after SSHing in.
#   usage:  ./deploy.sh
# Idempotent: safe to run on every release.
# =============================================================================
set -euo pipefail

APP_DIR="${APP_DIR:-$HOME/ta-field-guide}"
BRANCH="${BRANCH:-main}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"

cd "$APP_DIR"

echo "==> Pulling $BRANCH"
git fetch --prune origin
git reset --hard "origin/$BRANCH"

echo "==> Installing PHP deps (no dev, optimised autoload)"
$COMPOSER_BIN install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "==> Taking app down"
$PHP_BIN artisan down --render="errors::503" --retry=15 || true

trap '$PHP_BIN artisan up || true' EXIT

echo "==> Clearing stale caches"
$PHP_BIN artisan optimize:clear

echo "==> Running migrations"
$PHP_BIN artisan migrate --force

echo "==> Re-caching config / routes / views / events"
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan event:cache

echo "==> Linking storage"
$PHP_BIN artisan storage:link || true

echo "==> Restarting queue workers"
$PHP_BIN artisan queue:restart

echo "==> Bringing app up"
$PHP_BIN artisan up
trap - EXIT

echo "==> Deploy complete: $(git rev-parse --short HEAD)"
