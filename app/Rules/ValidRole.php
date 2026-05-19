<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRole implements ValidationRule
{
    protected array $roles = ['admin', 'organizer', 'attendee'];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, $this->roles, true)) {
            $fail('The :attribute must be one of: ' . implode(', ', $this->roles) . '.');
        }
    }
}
