<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\ConviteService;

class ConviteController extends ApiController
{
    protected function service(): ApiService
    {
        return app(ConviteService::class);
    }

    public function getEmailConviteAceito()
    {
        return $this->service()->getEmailConviteAceito();
    }

    public function getEmailConviteNaoAceito()
    {
        return $this->service()->getEmailConviteNaoAceito();
    }
}
