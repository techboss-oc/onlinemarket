<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferring to Secure Checkout...</title>
    <style>
        body { margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; font-family: system-ui, -apple-system, sans-serif; background-color: #f8fafc; color: #334155; }
        .spinner { width: 40px; height: 40px; border: 4px solid #cbd5e1; border-top-color: #f97316; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body onload="document.getElementById('checkout-form').submit();">
    <div class="spinner"></div>
    <h2 style="margin: 0;">Transferring to Secure Gateway...</h2>
    <p style="color: #64748b;">Please do not close this window.</p>

    <!-- Hidden form that auto-submits via POST to the generic checkout route -->
    <form id="checkout-form" action="{{ route('payment.checkout') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="transaction_id" value="{{ $transaction_id }}">
        <input type="hidden" name="provider" value="{{ $gateway }}">
    </form>
</body>
</html>
