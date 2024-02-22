<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxpayerRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'email',
            'gender' => 'required',
            'id_type' => 'required',
            'id_number' => 'required|string',

            'mobilephone' => [
                'required',
                'string',
                'min:8',
                'max:8',
                new \App\Rules\ValidPhoneNumber,
            ],
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'address' => 'required|string',
            //'canton' => 'required',
            'town_id' => 'required',
            'erea_id' => 'required',
            'zone_id' => 'required',
            'avatar' => 'nullable|sometimes|image|max:1024',
        ];
    }
}
