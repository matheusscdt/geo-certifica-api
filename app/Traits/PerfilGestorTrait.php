<?php

namespace App\Traits;

use Illuminate\Support\MessageBag;
use Prettus\Validator\Exceptions\ValidatorException;

trait PerfilGestorTrait
{
    public function getPerfil()
    {
        if (auth()?->user()?->isGestorPerfilAtivo()) {
            return auth()?->user()->gestorPerfilAtivo()->perfil_id;
        }

        return getPerfilId();
    }

    public function validarAcaoPerfilGestor(): void
    {
        if (!auth()?->user()?->isGestorPerfilAtivo()) {
            throw new ValidatorException(new MessageBag([
                "perfil_id" => "Somente o gestor pode realizar essa ação."
            ]));
        }
    }
}
