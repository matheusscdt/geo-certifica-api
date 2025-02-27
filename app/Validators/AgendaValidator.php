<?php

namespace App\Validators;

use App\Models\Agenda;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;

class AgendaValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator, Request $request)
    {
        $perfilId = request()->get('perfil_id') ?? getPerfilId();

        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'perfil_id' => ['nullable', "uuid", 'exists:perfil,id'],
                'nome' => ['required', 'max:255'],
                'email' => ['required', 'email', function ($attribute, $value, $fail) use ($perfilId) {
                    if (Agenda::where('email', $value)->whereHas('perfilAgenda', fn ($query) => $query->where('perfil_id', $perfilId))->exists()) {
                        $fail('E-mail já cadastrado na agenda para este perfil.');
                    }
                }],
            ],
            ValidatorInterface::RULE_UPDATE => [
                'perfil_id' => ['nullable', "uuid", 'exists:perfil,id'],
                'nome' => ['sometimes', 'max:255'],
                'email' => ['sometimes', 'email', function ($attribute, $value, $fail) use ($perfilId, $request) {
                    if (Agenda::where('email', $value)->where('id', '!=', $request->route('agenda'))->whereHas('perfilAgenda', fn ($query) => $query->where('perfil_id', $perfilId))->exists()) {
                        $fail('E-mail já cadastrado na agenda para este perfil.');
                    }
                }],
            ]
        ];

        parent::__construct($validator);
    }
}
