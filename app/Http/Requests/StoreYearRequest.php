<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|digits:4|unique:years,name',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE',
        ];
    }
}
