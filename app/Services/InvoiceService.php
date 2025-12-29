<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use Cloudinary\Cloudinary;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public static function generate(Booking $booking)
    {
        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'number' => 'INV-' . now()->format('Ymd') . '-' . $booking->id,
            'amount' => $booking->net_amount,
            'platform_fee' => $booking->platform_fee,
            'net_amount' => $booking->owner_earning,
        ]);

        // $pdf = Pdf::loadView('pdf.invoice', compact('booking', 'invoice'));

        // $pdf = Pdf::loadView(
        //     'pdf.invoice',
        //     compact('booking', 'invoice')
        // )->setPaper('a4');

        $pdf = Pdf::loadView('pdf.invoice', [
            'booking' => $booking,
            'invoice' => $invoice,
        ]);

        $cloudinary = new Cloudinary();
        $upload = $cloudinary->uploadApi()->upload(
            'data:application/pdf;base64,' . base64_encode($pdf->output()),
            [
                'folder' => 'invoices',
                'public_id' => $invoice->number,
                'resource_type' => 'raw', // IMPORTANT
            ]
        );

        // $path = "invoices/{$invoice->invoice_number}.pdf";
        // $path = "invoices/{$invoice->number}.pdf";

        // Storage::put($path, $pdf->output());
        // Storage::disk('public')->put($path, $pdf->output());

        // $invoice->update(['pdf_path' => $path]);

        $invoice->update([
            'pdf_path' => $upload['secure_url'],
            'pdf_public_id' => $upload['public_id'],
        ]);

        return $invoice;
    }

    //
    public function createForBooking(Booking $booking): Invoice
    {
        // Idempotency: never create twice
        if ($booking->invoice) {
            return $booking->invoice;
        }

        return Invoice::create([
            'booking_id' => $booking->id,
            'reference' => 'INV-' . Str::upper(Str::random(10)),
            'amount' => $booking->amount,
            'status' => 'unpaid',
            'issued_at' => now(),
        ]);
    }

    public function markAsPaid(Invoice $invoice): void
    {
        if ($invoice->status === 'paid') {
            return;
        }

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    //
    public function finalizeInvoice(Invoice $invoice)
    {
        if ($invoice->pdf_url) {
            return;
        }

        // 1. Generate PDF
        $pdfPath = app(PdfService::class)->generateInvoice($invoice);

        // 2. Upload to Cloudinary
        $url = app(CloudinaryService::class)->uploadInvoice($pdfPath);

        // 3. Save URL
        $invoice->update([
            'pdf_url' => $url,
        ]);

        // 4. Email receipt
        Mail::to($invoice->booking->user->email)
            ->send(new PaymentReceiptMail($invoice));
    }
}
