<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice – GreenPea Apartments</title>
</head>
<body style="margin:0; padding:0; background-color:#f6f7f9; font-family: Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f6f7f9; padding:20px 0;">
    <tr>
        <td align="center">

            <!-- MAIN CONTAINER -->
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:6px; overflow:hidden;">

                <!-- HEADER -->
                <tr>
                    <td style="background-color:#16a34a; padding:20px;">
                        <h1 style="margin:0; color:#ffffff; font-size:22px;">
                            GreenPea Apartments
                        </h1>
                        <p style="margin:5px 0 0; color:#e5f7ec; font-size:14px;">
                            Premium Apartment Rentals
                        </p>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td style="padding:24px; color:#333333; font-size:14px; line-height:1.6;">

                        <h2 style="margin-top:0; font-size:18px;">
                            Booking Invoice
                        </h2>

                        <p>Dear {{ $booking->guest_name }},</p>

                        <p>
                            Thank you for choosing <strong>GreenPea Apartments</strong>.
                            This email confirms that your booking has been successfully completed
                            and payment has been received.
                        </p>

                        <!-- DETAILS TABLE -->
                        <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse; margin:16px 0;">
                            <tr>
                                <td style="border:1px solid #e5e7eb;"><strong>Invoice Reference</strong></td>
                                <td style="border:1px solid #e5e7eb;">{{ $invoice->reference }}</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #e5e7eb;"><strong>Apartment</strong></td>
                                <td style="border:1px solid #e5e7eb;">{{ $booking->apartment->name }}</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #e5e7eb;"><strong>Location</strong></td>
                                <td style="border:1px solid #e5e7eb;">
                                    {{ $booking->apartment->property->city }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #e5e7eb;"><strong>Booking Period</strong></td>
                                <td style="border:1px solid #e5e7eb;">
                                    {{ $booking->start_date->format('d M Y') }} –
                                    {{ $booking->end_date->format('d M Y') }}
                                </td>
                            </tr>
                        </table>

                        <!-- AMOUNT BREAKDOWN -->
                        <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse; margin:16px 0;">
                            <tr>
                                <td style="border:1px solid #e5e7eb;">Subtotal</td>
                                <td style="border:1px solid #e5e7eb;" align="right">
                                    ₦{{ number_format($invoice->total_amount, 2) }}
                                </td>
                            </tr>

                            {{-- VAT (optional, show only if applicable) --}}
                            @if(config('platform.vat_percent', 0) > 0)
                                @php
                                    $vat = round($invoice->total_amount * (config('platform.vat_percent') / 100), 2);
                                @endphp
                                <tr>
                                    <td style="border:1px solid #e5e7eb;">
                                        VAT ({{ config('platform.vat_percent') }}%)
                                    </td>
                                    <td style="border:1px solid #e5e7eb;" align="right">
                                        ₦{{ number_format($vat, 2) }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td style="border:1px solid #e5e7eb;"><strong>Total Paid</strong></td>
                                <td style="border:1px solid #e5e7eb;" align="right">
                                    <strong>₦{{ number_format($invoice->total_amount, 2) }}</strong>
                                </td>
                            </tr>
                        </table>

                        <!-- PAYMENT METHOD -->
                        <p>
                            <strong>Payment Method:</strong>
                            {{ strtoupper(optional($booking->payments->last())->gateway ?? 'N/A') }}
                        </p>

                        <p>
                            A detailed invoice in PDF format is attached to this email for your records.
                        </p>

                        <p>
                            If you have any questions regarding this invoice or your booking,
                            please contact our support team.
                        </p>

                        <p>
                            Kind regards,<br>
                            <strong>GreenPea Apartments</strong>
                        </p>

                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="background-color:#f3f4f6; padding:16px; text-align:center; font-size:12px; color:#6b7280;">
                        <p style="margin:0;">
                            GreenPea Apartments Ltd<br>
                            12 Adeola Odeku Street, Victoria Island, Lagos, Nigeria
                        </p>
                        <p style="margin:6px 0 0;">
                            Support:
                            <a href="mailto:support@greenpeaapartments.com" style="color:#16a34a;">
                                support@greenpeaapartments.com
                            </a>
                            | +234 800 000 0000
                        </p>
                        <p style="margin:6px 0 0;">
                            © {{ date('Y') }} GreenPea Apartments. All rights reserved.
                        </p>
                        <p style="margin:6px 0 0;">
                            This is an automated message. Please do not reply.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
