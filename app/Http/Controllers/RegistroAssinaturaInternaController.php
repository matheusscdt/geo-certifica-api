<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\RegistroAssinaturaInternaService;
use Illuminate\Http\Request;

class RegistroAssinaturaInternaController extends ApiController
{
    protected function service(): ApiService
    {
        return app(RegistroAssinaturaInternaService::class);
    }

    public function showToValidador($id)
    {
        return $this->service()->findByIdToValidador($id);
    }
}
