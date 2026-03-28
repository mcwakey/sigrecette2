<?php

namespace App\Http\Requests;

use App\Helpers\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InitiateMobilePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('peut ajouter un paiement') ?? false;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'phone_number' => ['required', 'string', 'regex:/^(\+229|00229)?[0-9]{8}$/'],
            'provider' => ['required', Rule::in(Constants::MOBILE_PROVIDERS)],
            'code' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.regex' => 'Le numéro de téléphone doit être un numéro valide.',
        ];
    }
}
