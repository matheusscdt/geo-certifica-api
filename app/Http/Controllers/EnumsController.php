<?php

namespace App\Http\Controllers;

use App\Enums\DashboardDocumentoPainelEnum;
use App\Enums\LembreteDocumentoEnum;
use App\Enums\StatusDocumentoEnum;

class EnumsController extends Controller
{
    public function dashboardDocumentos()
    {
        return DashboardDocumentoPainelEnum::getCollectionTransform()->jsonSerialize();
    }

    public function lembreteDocumentos()
    {
        return LembreteDocumentoEnum::getCollectionTransform()->jsonSerialize();
    }

    public function statusDocumento()
    {
        return StatusDocumentoEnum::getCollectionTransform()->jsonSerialize();
    }
}
