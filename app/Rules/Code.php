<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Code implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]+$/', $value)) {
            $fail('The :attribute format is invalid. It must start with a letter and may contain letters, numbers, or underscores.');
        }
    }
}
