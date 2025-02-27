<?php

namespace App\Observers;

use App\Models\Perfil;

class PerfilObserver
{
    public function creating(Perfil $perfil)
    {
        $perfil->proprietario = false;
    }
}
