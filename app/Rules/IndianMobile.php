<?php

namespace App\Rules;

use App\Support\MobileNumber;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validator wrapper around MobileNumber::toCanonical — any 10-digit Indian
 * mobile in any format is accepted; everything else is rejected with a
 * clear message.
 */
class IndianMobile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! MobileNumber::isValid(is_string($value) ? $value : (string) $value)) {
            $fail('Enter a valid 10-digit Indian mobile number. +91 is added automatically.');
        }
    }
}
