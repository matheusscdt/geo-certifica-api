<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\MensagemTemplateService;

class MensagemTemplateController extends ApiController
{
    protected function service(): ApiService
    {
        return app(MensagemTemplateService::class);
    }
}
