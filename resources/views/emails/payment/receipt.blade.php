@component('mail::message')
# Payment Received 🎉

Your payment for **{{ $invoice->booking->apartment->name }}** has been confirmed.

### Invoice Reference
{{ $invoice->reference }}

### Amount
₦{{ number_format($invoice->amount, 2) }}

@component('mail::button', ['url' => $invoice->pdf_url])
Download Receipt
@endcomponent

Thank you for staying with Greenpea 🇳🇬
@endcomponent
