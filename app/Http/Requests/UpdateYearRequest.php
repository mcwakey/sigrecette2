<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'digits:4', Rule::unique('years', 'name')->ignore($this->route('year'))],
            'status' => 'nullable|string|in:ACTIVE,INACTIVE',
        ];
    }
}
