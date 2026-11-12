#!/usr/bin/env bash
# Phase 6 restore rehearsal — proves backup/restore round-trip works.
# Writes a snapshot, mutates the DB, restores, asserts the mutation is gone.
# Runs against the local DB; NEVER point at a production backup.
set -euo pipefail

cd "$(dirname "$0")/../.."

echo "== take backup =="
BACKUP=$(php artisan backup:db | awk '{print $NF}')
echo "backup: $BACKUP"

echo "== mutate DB =="
php artisan tinker --execute="App\Models\Notice::query()->create(['legacy_id'=>'REH-MUT','slug'=>'restore-rehearse-marker-'.time(),'title'=>'MUT','summary'=>'mutation','published_on'=>now()->toDateString(),'status'=>'draft']);"
COUNT_AFTER_MUT=$(php artisan tinker --execute="echo App\Models\Notice::where('legacy_id','REH-MUT')->count();" | tail -1)
echo "mutation rows: $COUNT_AFTER_MUT"
test "$COUNT_AFTER_MUT" = "1" || { echo "mutation did not take, aborting"; exit 1; }

echo "== restore from backup =="
php artisan restore:db "$BACKUP"

echo "== verify mutation is gone =="
COUNT_AFTER_RESTORE=$(php artisan tinker --execute="echo App\Models\Notice::where('legacy_id','REH-MUT')->count();" | tail -1)
echo "mutation rows after restore: $COUNT_AFTER_RESTORE"
test "$COUNT_AFTER_RESTORE" = "0" || { echo "restore did NOT revert the mutation — FAIL"; exit 1; }

echo ""
echo "Restore rehearsal succeeded: backup -> mutate -> restore reverted the change."
