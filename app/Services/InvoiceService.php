<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Booking;
use App\Models\Invoice;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class InvoiceService
{
    /**
     * Create (or return existing) invoice for a booking
     */
    public function generateForBooking(Booking $booking): Invoice
    {
        if ($booking->invoice) {
            return $booking->invoice;
        }

        return DB::transaction(function () use ($booking) {

            $platformFee = $this->calculatePlatformFee($booking->amount);

            $invoice = Invoice::create([
                'booking_id'    => $booking->id,
                'number'     => $this->generateReference(),
                'amount'        => $booking->amount,
                'platform_fee'  => $platformFee,
                'net_amount'    => $booking->amount - $platformFee,
                'status'        => InvoiceStatus::PAID->value,
                'issued_at'     => now(),
            ]);

            $pdfUrl = $this->generateAndUploadPdf($invoice, $booking);

            $invoice->update([
                'pdf_path' => $pdfUrl,
            ]);

            return $invoice;
        });
    }

    /**
     * Generate invoice PDF and upload to Cloudinary
     */
    // protected function generateAndUploadPdf(
    //     Invoice $invoice,
    //     Booking $booking
    // ): string {
    //     // 1Generate PDF
    //     $pdf = Pdf::loadView('pdf.invoice2', [
    //         'invoice' => $invoice,
    //         'booking' => $booking,
    //     ])->setPaper('a4');

    //     // Save temp file
    //     $tmpPath = storage_path("app/tmp/invoice-{$invoice->reference}.pdf");
    //     file_put_contents($tmpPath, $pdf->output());

    //     // Upload to Cloudinary (RAW)
    //     $upload = Cloudinary::uploadApi()->upload(
    //         $tmpPath,
    //         [
    //             'resource_type' => 'raw',
    //             'folder'        => 'invoices',
    //             'public_id'     => $invoice->reference,
    //             'overwrite'     => true,
    //         ]
    //     );

    //     // Cleanup
    //     @unlink($tmpPath);

    //     return $upload['secure_url'];
    // }

    protected function generateAndUploadPdf(
        Invoice $invoice,
        Booking $booking
    ): string {
        // ✅ Ensure invoice reference exists
        if (empty($invoice->reference)) {
            $invoice->update([
                'reference' => 'INV-' . strtoupper(Str::random(10)),
            ]);
        }

        // ✅ Ensure tmp directory exists
        $tmpDir = storage_path('app/tmp');
        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        // 1️⃣ Generate PDF
        $pdf = Pdf::loadView('pdf.invoice2', [
            'invoice' => $invoice,
            'booking' => $booking,
        ])->setPaper('a4');

        // 2️⃣ Write temp file
        $tmpPath = "{$tmpDir}/invoice-{$invoice->reference}.pdf";

        file_put_contents($tmpPath, $pdf->output());

        // 3️⃣ Upload to Cloudinary (RAW)
        $upload = Cloudinary::uploadApi()->upload(
            $tmpPath,
            [
                'resource_type' => 'raw',
                'folder'        => 'invoices',
                'public_id'     => $invoice->reference,
                'overwrite'     => true,
                'access_mode'   => 'public',
            ]
        );

        // 4️⃣ Cleanup temp file
        @unlink($tmpPath);

        return $upload['secure_url'];
    }
    /**
     * Mark invoice as paid (idempotent)
     */
    public function markAsPaid(Invoice $invoice): void
    {
        if ($invoice->status === InvoiceStatus::PAID->value) {
            return;
        }

        $invoice->update([
            'status'  => InvoiceStatus::PAID->value,
            'paid_at' => now(),
        ]);
    }

    /**
     * Platform fee calculation (centralized)
     */
    protected function calculatePlatformFee(float $amount): float
    {
        return round(
            $amount * (setting('platform_fee_percentage') / 100),
            2
        );
    }

    /**
     * Invoice reference generator
     */
    protected function generateReference(): string
    {
        return 'INV-' . Str::upper(Str::random(10));
    }
}
