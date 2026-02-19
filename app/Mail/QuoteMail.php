<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Quote $quote)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Teklif Formu: ' . $this->quote->number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quote',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdfService = app(\App\Services\QuotePdfService::class);
        $data = $pdfService->prepareData($this->quote);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotes.premium', $data);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), $this->quote->number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
