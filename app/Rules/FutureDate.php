<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FutureDate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $date = strtotime($value);
        if ($date === false || $date <= time()) {
            $fail('The :attribute must be a date in the future.');
        }
    }
}
