<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\DocumentoService;
use Illuminate\Http\Request;

class DocumentoController extends ApiController
{
    protected function service(): ApiService
    {
        return app(DocumentoService::class);
    }

    public function enviar(Request $request)
    {
        return $this->service()->enviar($request);
    }

    public function dashboard()
    {
        return $this->service()->dashboard();
    }

    public function cancelar($id)
    {
        return $this->service()->cancelar($id);
    }

    public function deleteTrash($id)
    {
        return $this->service()->deleteTrash($id);
    }

    public function cleanTrash()
    {
        return $this->service()->cleanTrash();
    }
}
