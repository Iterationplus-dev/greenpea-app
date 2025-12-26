<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Cloudinary\Cloudinary;

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
}
