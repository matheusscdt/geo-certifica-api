<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\CertificadoService;

class CertificadoController extends ApiController
{
    protected function service(): ApiService
    {
        return app(CertificadoService::class);
    }

    public function download($id)
    {
        return $this->service()->download($id);
    }

    public function selecionar($id)
    {
        return $this->service()->selecionar($id);
    }
}
