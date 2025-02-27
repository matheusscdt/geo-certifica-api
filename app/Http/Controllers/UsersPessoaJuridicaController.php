<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\UserPessoaJuridicaService;

class UsersPessoaJuridicaController extends ApiController
{
    protected function service(): ApiService
    {
        return app(UserPessoaJuridicaService::class);
    }
}
