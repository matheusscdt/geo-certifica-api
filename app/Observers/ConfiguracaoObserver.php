<?php

namespace App\Observers;

use App\Models\Configuracao;

class ConfiguracaoObserver
{
    public function creating(Configuracao $configuracao): void
    {
        $configuracao->data_limite_lembrete = $configuracao->calcularDataLimiteLembrete();
    }

    public function updating(Configuracao $configuracao): void
    {
        $configuracao->data_limite_lembrete = $configuracao->calcularDataLimiteLembrete();
    }
}
