<!doctype html>
<html>
<body style="font-family: system-ui, sans-serif; color: #1e2926; max-width: 560px; margin: 24px auto; line-height: 1.5;">
    <p>Thank you for your order.</p>
    <p><strong>Order reference:</strong> {{ $order->order_ref }}<br>
       <strong>Total:</strong> {{ $order->totalInrFormatted() }}<br>
       <strong>Paid on:</strong> {{ $order->paid_at?->toDayDateTimeString() }}</p>
    <p>To set your password and access study materials, use the one-time link below. It expires in 48 hours.</p>
    <p><a href="{{ $setupUrl }}" style="background:#173d32;color:#fff;padding:10px 18px;text-decoration:none;border-radius:4px;">Set my password</a></p>
    <p style="font-size: 12px; color:#4a5551;">Your buyer login id is your order reference: <strong>{{ $order->order_ref }}</strong>.</p>
</body>
</html>
