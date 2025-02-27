<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Prettus\Validator\Contracts\ValidatorInterface;

class DocumentoEnviarValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'documento_id' => [
                    'required',
                    'uuid',
                    'exists:documento,id',
                    Rule::exists('documento', 'id')->whereNull('deleted_at')
                ]
            ]
        ];

        parent::__construct($validator);
    }
}
