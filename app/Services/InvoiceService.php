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

            $paidSoFar = $booking->paidAmount();

            $invoice = Invoice::create([
                'booking_id'    => $booking->id,
                'number'     => $this->generateReference(),

                'amount'        => $booking->amount,
                'platform_fee'  => $platformFee,
                'net_amount'    => $booking->amount - $platformFee,

                'amount_paid'  => $paidSoFar,
                'balance_due'  => $booking->amount - $paidSoFar,

                'status'        => $this->resolveStatus($booking->amount, $paidSoFar),
                'issued_at'     => now(),
            ]);

            $pdfUrl = $this->generateAndUploadPdf($invoice, $booking);

            $invoice->update([
                'pdf_path' => $pdfUrl,
            ]);

            return $invoice;
        });
    }

    protected function generateAndUploadPdf(
        Invoice $invoice,
        Booking $booking
    ): string {
        // Ensure invoice reference exists
        if (empty($invoice->number)) {
            $invoice->update([
                'number' => $this->generateReference(),
            ]);
        }

        // Ensure tmp directory exists
        $tmpDir = storage_path('app/tmp');
        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.invoice2', [
            'invoice' => $invoice,
            'booking' => $booking,
        ])->setPaper('a4');

        // Write temp file
        $tmpPath = "{$tmpDir}/invoice-{$invoice->number}.pdf";

        file_put_contents($tmpPath, $pdf->output());

        // Upload to Cloudinary (RAW)
        $upload = Cloudinary::uploadApi()->upload(
            $tmpPath,
            [
                'resource_type' => 'raw',
                'folder'        => 'invoices',
                'public_id'     => $invoice->number,
                'overwrite'     => true,
                'access_mode'   => 'public',
            ]
        );

        // Cleanup temp file
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

    public function refreshInvoiceTotals(Invoice $invoice): void
    {
        if (! $invoice) {
            return;
        }
        $paid = $invoice->booking->paidAmount();

        $invoice->update([
            'amount_paid' => $paid,
            'balance_due' => $invoice->amount - $paid,
            'status'      => $this->resolveStatus($invoice->amount, $paid),
            'paid_at'     => $paid >= $invoice->amount ? now() : null,
        ]);
    }

    protected function resolveStatus($total, $paid)
    {
        if ($paid <= 0) {
            return InvoiceStatus::UNPAID->value;
        }

        if ($paid < $total) {
            return InvoiceStatus::PARTIALLY_PAID->value;
        }

        return InvoiceStatus::PAID->value;
    }
}
