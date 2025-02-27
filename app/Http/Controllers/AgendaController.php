<?php

namespace App\Http\Controllers;

use App\Services\AgendaService;
use App\Services\ApiService;

class AgendaController extends ApiController
{
    protected function service(): ApiService
    {
        return app(AgendaService::class);
    }
}
