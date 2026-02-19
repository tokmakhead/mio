<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProviderRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'types' => ['required', 'array', 'min:1'],
            'types.*' => ['string'],
            'custom_type' => ['nullable', 'string', 'max:50'],
            'tax_office' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'sağlayıcı adı',
            'types' => 'hizmet türleri',
            'tax_office' => 'vergi dairesi',
            'tax_number' => 'vergi numarası',
            'address' => 'adres',
            'website' => 'website',
            'email' => 'e-posta',
            'phone' => 'telefon',
            'notes' => 'notlar',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'types.required' => 'En az bir hizmet türü seçmelisiniz.',
            'types.min' => 'En az bir hizmet türü seçmelisiniz.',
        ];
    }
}
