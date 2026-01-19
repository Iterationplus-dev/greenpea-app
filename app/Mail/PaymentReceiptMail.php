<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    // public function __construct(
    //     public Invoice $invoice
    // ) {}

    public function __construct(public $invoice)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Receipt – ' . $this->invoice->number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // view: 'emails.payment.receipt',
            markdown: 'emails.payment.receipt',
            with: [
                'invoice' => $this->invoice,
                'booking' => $this->invoice->booking,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->invoice->pdf_path)
                ->as('Receipt-' . $this->invoice->number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
