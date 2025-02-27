<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\ConfiguracaoService;

class ConfiguracaoController extends ApiController
{
    protected function service(): ApiService
    {
        return app(ConfiguracaoService::class);
    }
}
