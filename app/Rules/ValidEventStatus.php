<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEventStatus implements ValidationRule
{
    protected array $statuses = ['draft', 'published', 'cancelled'];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, $this->statuses, true)) {
            $fail('The :attribute must be one of: ' . implode(', ', $this->statuses) . '.');
        }
    }
}
