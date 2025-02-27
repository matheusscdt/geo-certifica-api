<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\UserPerfilService;

class UsersPerfilController extends ApiController
{
    protected function service(): ApiService
    {
        return app(UserPerfilService::class);
    }
}
