# Cloudflare R2 setup

Short walkthrough — the code side is already wired. If `R2_ACCESS_KEY_ID` + `R2_SECRET_ACCESS_KEY` + bucket vars are set in `.env`, both `public` and `private` disks route to R2 automatically; nothing else to change.

## 1. Create two buckets in Cloudflare

- `ta-public` — public bucket. Enable "Public access" or attach a custom domain / r2.dev subdomain for the public URL.
- `ta-private` — private bucket. **Do not** enable public access. Private files are served only through the app's `/study/download/{material}` route with a signed 60s URL.

## 2. Mint an R2 API token

Cloudflare dashboard → R2 → Manage R2 API Tokens → Create API token.
- Permissions: **Object Read & Write**
- Buckets: both of yours (`ta-public`, `ta-private`)
- TTL: unlimited (rotate yearly)

Copy the Access Key ID + Secret Access Key — you won't see the secret again.

## 3. Add these to `.env`

```env
R2_ACCOUNT_ID=<your-cloudflare-account-id>
R2_ACCESS_KEY_ID=<access key id>
R2_SECRET_ACCESS_KEY=<secret access key>
R2_PUBLIC_BUCKET=ta-public
R2_PRIVATE_BUCKET=ta-private
R2_PUBLIC_URL=https://pub-<hash>.r2.dev     # or a custom domain if you set one
```

(The config also accepts the generic `AWS_*` names as fallback for the token + bucket, but the dedicated `R2_*` names keep a single-bucket s3 setup separate.)

## 4. Verify from the CLI

```bash
php artisan r2:smoke
```

Expected output:

```
--- public disk (driver=s3, bucket=ta-public) ---
  ✓ wrote r2-smoke/...
  ✓ read back identical bytes
  ✓ deleted r2-smoke/...
--- private disk (driver=s3, bucket=ta-private) ---
  ✓ wrote r2-smoke/...
  ✓ read back identical bytes
  ✓ deleted r2-smoke/...
```

If you see `local` in the driver column, the env vars are not being read — check you reopened your shell and that `.env` is in the project root.

## 5. Move existing files up to R2 (if any)

The Phase 2 seeder wrote one public and one private synthetic PDF under `storage/app/`. For local dev those are fine; on staging/prod run:

```bash
# Replay the seed against R2
php artisan db:seed --class=Phase2DemoSeeder --force
```

Or upload a real PDF through the Filament admin (`/admin/pdfs`) — it uses the same disks, so it'll go straight to R2.

## Security notes

- The private R2 bucket **must never** have public access enabled. If someone needs the file, they go through `/study/download/{material}` so the app re-checks their entitlement on every request.
- Rotate the R2 API token when any staff person with access to the `.env` leaves.
- The app never logs the R2 secret. It does not appear in `storage/logs/laravel.log` or in `payment_attempts.raw_response_redacted_json`.
- `.env` is gitignored (confirm with `git check-ignore .env`). Never commit it.
