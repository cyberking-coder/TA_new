<!doctype html>
<html>
<body style="font-family: system-ui, sans-serif; color: #1e2926; max-width: 560px; margin: 24px auto; line-height: 1.5;">
    <p>Thank you for subscribing to {{ $audience }} alerts.</p>
    <p>To confirm your email and start receiving alerts, click the link below. It expires in 24 hours and works once.</p>
    <p><a href="{{ $verifyUrl }}" style="background:#173d32;color:#fff;padding:10px 18px;text-decoration:none;border-radius:4px;">Confirm my {{ $audience }} alerts</a></p>
    <p style="font-size: 13px; color:#4a5551;">If you did not request this, ignore this message. You can also <a href="{{ $unsubUrl }}">unsubscribe immediately</a> — your address will be added to our suppression list for this audience.</p>
    <p style="font-size: 12px; color:#4a5551;">This email was sent by the Territorial Army exam guide — an independent publisher.</p>
</body>
</html>
