<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\PastaService;

class PastaController extends ApiController
{
    protected function service(): ApiService
    {
        return app(PastaService::class);
    }
}
