<?php

namespace App\Observers;

use App\Enums\StatusDocumentoEnum;
use App\Models\Documento;

class DocumentoObserver
{
    public function creating(Documento $documento)
    {
        $documento->status_documento = StatusDocumentoEnum::Rascunho;
    }
}
