<h1>{{ __('New Quote') }}</h1>
<p>{{ __('Dear') }} {{ $quote->customer->name ?? __('Customer') }},</p>
<p>{{ __('We have prepared a quote with number :number as per your request, which is attached.', ['number' => $quote->number]) }}
</p>
<p><strong>{{ __('Total Amount') }}:</strong> {{ number_format($quote->grand_total, 2) }} {{ $quote->currency }}</p>
<p><strong>{{ __('Valid Until') }}:</strong> {{ $quote->valid_until->format('d.m.Y') }}</p>
<p>{{ __('You can reply to this email to accept our quote or ask any questions.') }}</p>
<br>
<p>{{ __('Best regards') }},<br>{{ config('app.name') }} {{ __('Team') }}</p>