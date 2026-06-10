<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = (string) $value;

        // Only minimum length is required
        if (mb_strlen($password) < 8 || mb_strlen($password) > 128) {
            $fail(__('register.error_password_min'));
            return;
        }
    }
}