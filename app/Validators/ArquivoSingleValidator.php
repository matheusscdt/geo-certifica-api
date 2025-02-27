<?php

namespace App\Validators;

use Prettus\Validator\Contracts\ValidatorInterface;

class ArquivoSingleValidator extends BaseLaravelValidator
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'arquivo' => 'required|file|mimes:jpeg,png,jpg,gif,pdf'
        ]
    ];
}
