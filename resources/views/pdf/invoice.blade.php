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
    {{-- <p><strong>City:</strong> {{ $apartment->property->city }}</p> --}}

    <hr>

    <p><strong>Booking Period:</strong>
        {{ $booking->start_date }} → {{ $booking->end_date }}
    </p>

    <h2>Total: ₦{{ number_format($invoice->amount, 2) }}</h2>

    <p>Status: {{ strtoupper($invoice->status) }}</p>

    <p>Thank you for choosing Greenpea.</p>
</body>
</html>
