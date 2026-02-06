<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:individual,corporate'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'mobile_phone' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:2'],
            'tax_or_identity_number' => ['nullable', 'string', 'max:255'],
            'invoice_address' => ['nullable', 'array'],
            'invoice_address.address' => ['nullable', 'string'],
            'invoice_address.city' => ['nullable', 'string', 'max:255'],
            'invoice_address.district' => ['nullable', 'string', 'max:255'],
            'invoice_address.postal_code' => ['nullable', 'string', 'max:20'],
            'invoice_address.country' => ['nullable', 'string', 'max:2'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'type' => 'müşteri türü',
            'name' => 'ad/firma adı',
            'email' => 'e-posta',
            'phone' => 'telefon',
            'mobile_phone' => 'mobil telefon',
            'website' => 'website',
            'address' => 'adres',
            'city' => 'şehir',
            'district' => 'ilçe',
            'postal_code' => 'posta kodu',
            'country' => 'ülke',
            'tax_or_identity_number' => 'vergi/TC no',
            'status' => 'durum',
            'notes' => 'notlar',
        ];
    }
}
