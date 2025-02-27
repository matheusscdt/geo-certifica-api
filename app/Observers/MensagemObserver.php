<?php

namespace App\Observers;

use App\Enums\StatusDocumentoEnum;
use App\Models\Mensagem;

class MensagemObserver
{
    public function created(Mensagem $mensagem): void
    {

    }
}
