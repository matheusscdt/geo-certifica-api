<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\UserPessoaFisicaService;

class UsersPessoaFisicaController extends ApiController
{
    protected function service(): ApiService
    {
        return app(UserPessoaFisicaService::class);
    }
}
