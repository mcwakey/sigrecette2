<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Propaganistas\LaravelPhone\Rules\Phone;


class ValidPhoneNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $phone = new Phone();
            $phone->country(['TG','GH','BJ'])->type('mobile');
            return true;
        } catch (Exception $e) {
            return false; 
        }
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The phone number is not valid.';
    }
}
