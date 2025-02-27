<?php

namespace App\Validators;

use App\Enums\LembreteDocumentoEnum;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Prettus\Validator\Contracts\ValidatorInterface;

class ConfiguracaoValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'documento_id' => ['required', 'uuid', 'exists:documento,id', 'unique:configuracao,documento_id'],
                'lembrete_documento' => ['required', Rule::in(LembreteDocumentoEnum::cases())],
                'data_limite_assinatura' => ['required', 'date:Y-m-d', 'after_or_equal:today'],
            ],
            ValidatorInterface::RULE_UPDATE => [
                'lembrete_documento' => ['required', Rule::in(LembreteDocumentoEnum::cases())],
                'data_limite_assinatura' => ['required', 'date:Y-m-d', 'after_or_equal:today'],
            ]
        ];

        $this->messages = [
            'data_limite_assinatura.after_or_equal' => 'A data limite para assinatura deve ser igual ou superior a data atual.',
        ];

        parent::__construct($validator);
    }
}
