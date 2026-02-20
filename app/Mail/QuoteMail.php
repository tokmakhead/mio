<?php

namespace App\Mail;

use App\Models\EmailTemplate;
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

    public string $body;
    public string $renderedSubject;
    public bool $useTemplate;

    public function __construct(public Quote $quote)
    {
        $template = EmailTemplate::where('type', 'quote')->where('enabled', true)->first();

        if ($template) {
            $settings = \App\Models\SystemSetting::first();
            $brand = \App\Models\BrandSetting::all()->pluck('value', 'key');

            $vars = [
                'customer_name' => $quote->customer->name ?? 'Müşteri',
                'quote_number' => $quote->number,
                'quote_date' => $quote->created_at ? $quote->created_at->format('d.m.Y') : now()->format('d.m.Y'),
                'grand_total' => number_format($quote->grand_total, 2) . ' ' . $quote->currency,
                'brand_name' => $brand['site_title'] ?? ($settings->site_name ?? 'MIONEX'),
                'brand_logo' => $brand['logo_path'] ?? ($settings->logo_path ?? ''),
                'brand_color' => $brand['primary_color'] ?? '#dc2626',
                'app_name' => $settings->site_name ?? 'MIONEX',
                'app_url' => config('app.url'),
            ];
            $this->body = $template->render($vars);
            $this->renderedSubject = $template->renderSubject($vars);
            $this->useTemplate = true;
        } else {
            $this->body = '';
            $this->renderedSubject = 'Teklif: ' . $quote->number;
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
            view: $this->useTemplate ? 'emails.dynamic' : 'emails.quote',
        );
    }

    public function attachments(): array
    {
        $pdfService = app(\App\Services\QuotePdfService::class);
        $data = $pdfService->prepareData($this->quote);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotes.premium', $data);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn() => $pdf->output(),
                $this->quote->number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
