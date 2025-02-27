<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        $user->gestor = false;
        $user->ativo = false;
    }
}
