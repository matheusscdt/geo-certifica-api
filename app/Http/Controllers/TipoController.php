<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\TipoService;

class TipoController extends ApiController
{
    protected function service(): ApiService
    {
        return app(TipoService::class);
    }
}
