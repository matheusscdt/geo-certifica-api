<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\AssinaturaService;
use Illuminate\Http\Request;

class AssinaturaController extends ApiController
{
    protected function service(): ApiService
    {
        return app(AssinaturaService::class);
    }

    public function getRelatorioAssinaturas()
    {
        return $this->service()->getRelatorioAssinaturas();
    }

    public function getRelatorioAssinaturasPdf()
    {
        return $this->service()->getRelatorioAssinaturasPdf();
    }

    public function getFinalizacaoAssinaturas()
    {
        return $this->service()->getFinalizacaoAssinaturas();
    }

    public function finalizarAssinaturasPorDocumento($documentoId)
    {
        return $this->service()->finalizarAssinaturasPorDocumento($documentoId);
    }

    public function finalizacao(Request $request, $id)
    {
        return $this->service()->finalizacao($request, $id);
    }
}
