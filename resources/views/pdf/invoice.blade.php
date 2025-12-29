{{-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            margin-bottom: 20px;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>{{ config('app.name') }}</h2>
    <p>Invoice #: {{ $invoice->number }}</p>
    <p>Date: {{ $invoice->created_at->format('d M Y') }}</p>
</div>

<p>
    <strong>Billed To:</strong><br>
    {{ $booking->guest->name }}<br>
    {{ $booking->guest->email }}
</p>

<hr>

<p>
    Apartment: {{ $booking->apartment->name }}<br>
    Period: {{ $booking->start_date }} → {{ $booking->end_date }}
</p>

<hr>

<table width="100%">
    <tr>
        <td>Total Amount</td>
        <td align="right">₦{{ number_format($invoice->amount, 2) }}</td>
    </tr>
    <tr>
        <td>Platform Fee</td>
        <td align="right">₦{{ number_format($invoice->platform_fee, 2) }}</td>
    </tr>
    <tr class="total">
        <td>Net Paid</td>
        <td align="right">₦{{ number_format($invoice->net_amount, 2) }}</td>
    </tr>
</table>

</body>
</html> --}}


{{-- Next Template --}}


<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 14px; }
        h1 { font-size: 20px; }
    </style>
</head>
<body>
    <h1>Greenpea Apartments</h1>
    <p><strong>Invoice:</strong> {{ $invoice->reference }}</p>
    <p><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>

    <hr>

    <p><strong>Guest:</strong> {{ $user->name }} ({{ $user->email }})</p>
    <p><strong>Apartment:</strong> {{ $apartment->name }}</p>
    <p><strong>City:</strong> {{ $apartment->property->city }}</p>

    <hr>

    <p><strong>Booking Period:</strong>
        {{ $booking->start_date }} → {{ $booking->end_date }}
    </p>

    <h2>Total: ₦{{ number_format($invoice->amount, 2) }}</h2>

    <p>Status: {{ strtoupper($invoice->status) }}</p>

    <p>Thank you for choosing Greenpea.</p>
</body>
</html>
