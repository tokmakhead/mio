<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #374151; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .header { border-bottom: 2px solid #a82244; padding-bottom: 10px; margin-bottom: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #a82244; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #a82244; margin: 0;">{{ __('New Invoice') }}: {{ $invoice->number }}</h2>
        </div>
        
        <p>{{ __('Dear') }} <strong>{{ $invoice->customer->name }}</strong>,</p>
        
        <p>{{ __('A new invoice with number :number has been created for your account.', ['number' => $invoice->number]) }}</p>
        
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>{{ __('Total Amount') }}:</strong> {{ number_format($invoice->grand_total, 2) }} {{ $invoice->currency }}</p>
            <p style="margin: 5px 0;"><strong>{{ __('Due Date') }}:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}</p>
        </div>
        
        <p>{{ __('You can find the detailed invoice in the attached PDF file. You can also view the invoice online by clicking the button below:') }}</p>
        
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn">{{ __('View Invoice') }}</a>
        </p>
        
        <p>{{ __('If you have any questions, you can reply to this email.') }}</p>
        
        <div class="footer">
            <p>{{ __('This email was sent automatically by the :app system.', ['app' => config('app.name')]) }}</p>
        </div>
    </div>
</body>
</html>
