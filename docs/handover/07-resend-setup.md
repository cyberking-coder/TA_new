# Resend mail transport

Short walkthrough — the Laravel side is already wired. Fill a handful of env vars and verify your domain, and every verification email, order receipt and newsletter will route through Resend.

## 1. Create the Resend account

- Sign up at https://resend.com.
- Settings → Domains → Add domain → enter your sending domain (e.g. `jointerritorialarmy.com`).
- Resend shows you the required DNS records (SPF `TXT`, DKIM `TXT` × 3, usually a MX for return-path). Add them at your DNS provider.
- Wait ~15 min for propagation. Resend's domain page shows a green tick when it's ready.

## 2. Create an API key

- Settings → API Keys → Create → choose "Sending access". Copy the `re_...` secret.

## 3. Add to `.env`

```env
MAIL_MAILER=resend
RESEND_KEY=re_XXXXXXXXXXXXXXXXXXXXXXXXX
MAIL_FROM_ADDRESS=alerts@yourdomain.com     # must be on the verified domain
MAIL_FROM_NAME="Territorial Army exam guide"
```

Then clear the config cache so Laravel picks it up:

```bash
php artisan config:clear
```

## 4. Smoke-test

```bash
php artisan resend:smoke you@yourdomain.com
```

The command prints the transport it used, attempts to send a tiny plain-text email, and reports success or the exact failure reason. On success, check your inbox AND the Resend dashboard — you'll see the message under **Logs** with status, opens and clicks.

For a subject / body of your choice:

```bash
php artisan resend:smoke you@yourdomain.com \
  --subject="Launch-day checkpoint" \
  --body="If this arrives, Resend is live."
```

## What gets sent through Resend once this is wired

- `VerifySubscriptionMail` — officer / soldier alert signup verification.
- `OrderReceiptMail` — after a confirmed payment, carrying the password-setup link.
- `NewsletterMail` — bulk sends from the admin's Newsletters resource (one API call per recipient; Resend handles the rate limiting).

## Guardrails

- The `NewsletterSender` service re-checks `Suppression::isSuppressed()` for every recipient **at send time**, not queue time. Unsubscribes that happen between composing a newsletter and the job running are respected.
- `MAIL_FROM_ADDRESS` must live on the verified Resend domain. Mismatches are rejected by Resend before any mail leaves.
- If the Resend account is still sandboxed (brand-new accounts), only addresses you verified can receive mail. Request production access to lift the sandbox once DNS is green.
- Rotate `RESEND_KEY` when any staff with `.env` access leaves. The old key can be revoked in the dashboard.
- Resend's dashboard logs show the full message — don't send anything through smoke testing that you wouldn't put in that log.

## Rollback

To stop sending real mail at any point (e.g. launch-day triage):

```env
MAIL_MAILER=log
```

```bash
php artisan config:clear
```

That diverts all outgoing mail back to `storage/logs/laravel.log`. No code change required.
