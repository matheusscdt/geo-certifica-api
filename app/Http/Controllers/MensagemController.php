<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\MensagemService;

class MensagemController extends ApiController
{
    protected function service(): ApiService
    {
        return app(MensagemService::class);
    }
}
