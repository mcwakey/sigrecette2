<?php

namespace App\Rules;

use libphonenumber\PhoneNumberUtil;
use Illuminate\Contracts\Validation\Rule;

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
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        try {
            $parsedNumber = $phoneNumberUtil->parse($value, 'TG');
            return $phoneNumberUtil->isValidNumber($parsedNumber);
        } catch (\libphonenumber\NumberParseException $e) {
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
