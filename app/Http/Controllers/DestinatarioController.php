<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\DestinatarioService;

class DestinatarioController extends ApiController
{
    protected function service(): ApiService
    {
        return app(DestinatarioService::class);
    }

    public function showToAssinatura($id)
    {
        return $this->service()->findByIdToAssinatura($id);
    }
}
