<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends ApiController
{
    protected function service(): ApiService
    {
        return app(LoginService::class);
    }

    public function login(Request $request)
    {
        return $this->service()->login($request);
    }

    public function logout(): JsonResponse
    {
        return $this->service()->logout();
    }

    public function refresh(): JsonResponse
    {
        return  $this->service()->refresh();
    }

    public function me()
    {
        return $this->show(auth()->id());
    }
}
