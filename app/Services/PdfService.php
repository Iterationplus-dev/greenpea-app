<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function generateInvoice(Invoice $invoice): string
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'booking' => $invoice->booking,
            'apartment' => $invoice->booking->apartment,
            'user' => $invoice->booking->user,
        ]);

        $path = storage_path('app/invoices/' . $invoice->reference . '.pdf');

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $pdf->save($path);

        return $path;
    }
}
