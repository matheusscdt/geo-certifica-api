<?php

namespace App\Observers;

use App\Models\Autorizacao;

class AutorizacaoObserver
{
    public function creating(Autorizacao $autorizacao): void
    {
        $autorizacao->codigo = gerarCodigo();
        $autorizacao->data_validade = now()->addHour();
        $autorizacao->autorizado = false;
    }
}
