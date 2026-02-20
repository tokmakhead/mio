<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $body;
    public string $renderedSubject;
    public bool $useTemplate;

    public function __construct(public Invoice $invoice)
    {
        $template = EmailTemplate::where('type', 'invoice')->where('enabled', true)->first();

        if ($template) {
            $vars = [
                'customer_name' => $invoice->customer->name ?? 'Müşteri',
                'invoice_number' => $invoice->number,
            ];
            $this->body = $template->render($vars);
            $this->renderedSubject = $template->renderSubject($vars);
            $this->useTemplate = true;
        } else {
            $this->body = '';
            $this->renderedSubject = 'Fatura: ' . $invoice->number;
            $this->useTemplate = false;
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->renderedSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: $this->useTemplate ? 'emails.dynamic' : 'emails.invoice',
        );
    }

    public function attachments(): array
    {
        $pdfService = app(\App\Services\InvoicePdfService::class);
        $data = $pdfService->prepareData($this->invoice);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.premium', $data);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn() => $pdf->output(),
                $this->invoice->number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
