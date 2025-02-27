<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class DocumentoValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'pasta_id' => ['required', 'integer', 'exists:pasta,id']
            ],
            ValidatorInterface::RULE_UPDATE => [
                'pasta_id' => ['sometimes', 'integer', 'exists:pasta,id']
            ]
        ];

        parent::__construct($validator);
    }
}
