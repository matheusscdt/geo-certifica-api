<?php

namespace App\Validators;

use App\Models\Tipo;
use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class DestinatarioValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'documento_id' => ['required', "uuid", 'exists:documento,id'],
                'agenda_id' => ['required', "integer", 'exists:agenda,id'],
                'tipo_id' => ['required', "integer", 'exists:tipo,id', function ($attribute, $value, $fail) {
                    if (Tipo::find($value)->perfil_id !== getPerfilId()) {
                        $fail('Tipo não pertence ao perfil.');
                    }
                }]
            ],
            ValidatorInterface::RULE_UPDATE => [
                'tipo_id' => ['sometimes', "integer", 'exists:tipo,id', function ($attribute, $value, $fail) {
                    if (Tipo::find($value)->perfil_id !== getPerfilId()) {
                        $fail('Tipo não pertence ao perfil.');
                    }
                }]
            ]
        ];

        $this->messages = [
            'agenda_id.required' => 'O Nome da Agenda é obrigatório.',
            'tipo_id.required' => 'O Tipo do Destinatário é obrigatório.'
        ];

        parent::__construct($validator);
    }
}
