<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'provider_id' => ['required', 'exists:providers,id'],
            'type' => ['required', 'in:hosting,domain,ssl,email,other'],
            'name' => ['required', 'string', 'max:255'],
            'identifier_code' => ['required', 'string', 'max:20', 'unique:services,identifier_code'],
            'cycle' => ['required', 'in:monthly,quarterly,yearly,biennial,custom'],
            'payment_type' => ['required', 'in:installment,upfront'],
            'status' => ['required', 'in:active,suspended,cancelled,expired'],
            'currency' => ['required', 'in:TRY,USD,EUR,GBP'],
            'price' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'müşteri',
            'provider_id' => 'sağlayıcı',
            'type' => 'hizmet türü',
            'name' => 'hizmet adı',
            'identifier_code' => 'tanımlayıcı kod',
            'cycle' => 'dönem',
            'payment_type' => 'ödeme tipi',
            'status' => 'durum',
            'currency' => 'para birimi',
            'price' => 'fiyat',
            'start_date' => 'başlangıç tarihi',
            'end_date' => 'bitiş tarihi',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'end_date.after' => 'Bitiş tarihi, başlangıç tarihinden sonra olmalıdır.',
        ];
    }
}
