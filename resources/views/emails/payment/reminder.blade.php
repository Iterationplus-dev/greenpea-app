<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Reminder – {{ $invoice->number }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:20px;">
<tr>
<td align="center">

    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;">

        <!-- HEADER -->
        <tr>
            <td style="background:#dc3545;padding:25px;color:#fff;text-align:center;">
                <h2 style="margin:0;">GreenPea Apartments</h2>
                <p style="margin:5px 0 0;">Payment Reminder</p>
            </td>
        </tr>

        <!-- BODY -->
        <tr>
            <td style="padding:25px;color:#333;">

                <p>Hello <strong>{{ $booking->guest_name }}</strong>,</p>

                <p>
                    This is a friendly reminder that an outstanding balance remains on your booking invoice.
                </p>

                <table width="100%" cellpadding="8" cellspacing="0" style="margin:20px 0;border:1px solid #eaeaea;">
                    <tr>
                        <td><strong>Invoice Number:</strong></td>
                        <td>{{ $invoice->number }}</td>
                    </tr>

                    <tr>
                        <td><strong>Apartment:</strong></td>
                        <td>{{ $booking->apartment->name ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <td><strong>Stay Dates:</strong></td>
                        <td>
                            {{ $booking->start_date->format('M d, Y') }}
                            →
                            {{ $booking->end_date->format('M d, Y') }}
                        </td>
                    </tr>
                </table>

                <h4>Outstanding Balance</h4>

                <table width="100%" cellpadding="8" cellspacing="0" style="border:1px solid #eaeaea;">
                    <tr>
                        <td>Total Amount:</td>
                        <td align="right">₦{{ number_format($invoice->amount, 2) }}</td>
                    </tr>

                    <tr>
                        <td>Amount Paid:</td>
                        <td align="right">₦{{ number_format($invoice->amount_paid, 2) }}</td>
                    </tr>

                    <tr>
                        <td><strong>Balance Due:</strong></td>
                        <td align="right">
                            <strong style="color:#dc3545;">
                                ₦{{ number_format($invoice->balance_due, 2) }}
                            </strong>
                        </td>
                    </tr>
                </table>

                <p style="margin-top:20px;">
                    Please make payment at your earliest convenience to avoid any disruption to your reservation.
                </p>

                @if($booking->payment_link)
                    <p style="text-align:center;margin:25px 0;">
                        <a href="{{ $booking->payment_link }}"
                           style="background:#0d6efd;color:#fff;padding:12px 20px;
                                  text-decoration:none;border-radius:5px;">
                            Pay Now
                        </a>
                    </p>
                @endif

                <p>
                    The original invoice is attached to this email for your reference.
                </p>

                <p>
                    If you have already made payment, kindly ignore this reminder.
                </p>

                <p>
                    For any assistance, contact us at
                    <strong>{{ config('mail.from.address') }}</strong>
                </p>

                <p>Kind regards,<br>
                <strong>GreenPea Apartments Team</strong></p>

            </td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td style="background:#f1f1f1;padding:15px;text-align:center;font-size:12px;color:#777;">
                © {{ date('Y') }} GreenPea Apartments. All rights reserved.
            </td>
        </tr>

    </table>

</td>
</tr>
</table>

</body>
</html>
