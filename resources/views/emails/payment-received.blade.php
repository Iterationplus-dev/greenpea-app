<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Received</title>
</head>
<body>
    <h2>Payment Successful 🎉</h2>

    <p>Hello {{ $payment->booking->guest->name }},</p>

    <p>We’ve received your payment for the apartment booking.</p>

    <ul>
        <li><strong>Amount:</strong> ₦{{ number_format($payment->amount, 2) }}</li>
        <li><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</li>
        <li><strong>Reference:</strong> {{ $payment->reference }}</li>
    </ul>

    <p>
        <strong>Status:</strong>
        {{ $payment->booking->status === 'paid' ? 'Fully Paid' : 'Partially Paid' }}
    </p>

    <p>Thank you for choosing GreenPea Apartments.</p>

    <p>
        — {{ config('app.name') }}
    </p>
</body>
</html>
