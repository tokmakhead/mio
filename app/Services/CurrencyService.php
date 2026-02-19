<?php

namespace App\Services;

class CurrencyService
{
    /**
     * Supported currencies.
     */
    protected $currencies = ['TRY', 'USD', 'EUR', 'GBP'];

    /**
     * Get currency symbols.
     */
    public function getSymbols()
    {
        return [
            'TRY' => '₺',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
        ];
    }

    /**
     * Get symbol for a currency code.
     */
    public function getSymbol(string $code)
    {
        $symbols = $this->getSymbols();
        return $symbols[strtoupper($code)] ?? $code;
    }

    /**
     * Placeholder for currency conversion.
     * In a full implementation, this would fetch live rates.
     */
    public function convert(float $amount, string $from, string $to, float $rate = 1.0)
    {
        if ($from === $to) {
            return $amount;
        }

        return $amount * $rate;
    }
}
