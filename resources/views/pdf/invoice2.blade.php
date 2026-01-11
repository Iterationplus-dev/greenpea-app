<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->reference }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { margin-bottom: 20px; }
        .total { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h2>GreenPea Apartments</h2>
    <p>Invoice: {{ $invoice->reference }}</p>
    <p>Date: {{ $invoice->created_at->format('d M Y') }}</p>
</div>

<p><strong>Guest:</strong> {{ $booking->guest_name }}</p>
<p><strong>Email:</strong> {{ $booking->guest_email }}</p>
<p><strong>Apartment:</strong> {{ $booking->apartment->name }}</p>

<hr>

<p class="total">
    Total Paid: ₦{{ number_format($invoice->total_amount, 2) }}
</p>

<p>Status: PAID</p>

</body>
</html>
