<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArquivoPfxRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value->getClientMimeType() !== 'application/x-pkcs12') {
            $fail('O arquivo deve ser do tipo .pfx');
        }
    }
}
