#!/usr/bin/env bash
# Phase 6 clean rebuild — verifies the repo boots from scratch.
# Run against a disposable staging worktree, NEVER production.
set -euo pipefail

cd "$(dirname "$0")/../.."

echo "== clean workdir =="
rm -rf vendor node_modules public/build database/database.sqlite .env

echo "== install =="
composer install --no-interaction --prefer-dist
npm install
npm run build

echo "== bootstrap env =="
cp .env.example .env
php artisan key:generate --ansi
touch database/database.sqlite

echo "== migrate + seed =="
php artisan migrate --force
php artisan db:seed --class=Phase2DemoSeeder --no-interaction

echo "== test suite =="
php artisan test

echo "== health =="
php artisan release:health

echo ""
echo "Clean rebuild succeeded. Run 'php artisan serve' to boot the app."
