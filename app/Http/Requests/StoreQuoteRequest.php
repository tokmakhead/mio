<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'valid_until' => ['required', 'date', 'after_or_equal:today'],
            'discount_type' => ['required', 'in:fixed,percentage'],
            'discount_rate' => ['nullable', 'numeric', 'min:0'],

            // Items validation
            'items' => ['required', 'array', 'min:1'],
            'items.*.service_id' => ['nullable', 'exists:services,id'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.vat_rate' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'müşteri',
            'valid_until' => 'geçerlilik tarihi',
            'currency' => 'para birimi',
            'items' => 'teklif kalemleri',
            'items.*.description' => 'açıklama',
            'items.*.qty' => 'miktar',
            'items.*.unit_price' => 'birim fiyat',
            'items.*.vat_rate' => 'KDV oranı',
        ];
    }
}
