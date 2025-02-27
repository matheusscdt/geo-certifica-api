<?php

namespace App\Validators;

use Prettus\Validator\Contracts\ValidatorInterface;

class ArquivoValidator extends BaseLaravelValidator
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'arquivos' => 'required|array',
            'arquivos.*' => 'required|file|mimes:jpeg,png,jpg,gif,pdf'
        ]
    ];
}
