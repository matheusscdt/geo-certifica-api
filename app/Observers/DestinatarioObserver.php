<?php

namespace App\Observers;

use App\Enums\StatusDocumentoEnum;
use App\Models\Destinatario;

class DestinatarioObserver
{
    public function created(Destinatario $destinatario): void
    {

    }
}
