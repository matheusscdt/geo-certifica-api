<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UsersController extends ApiController
{
    protected function service(): ApiService
    {
        return app(UserService::class);
    }

    public function ativar(Request $request)
    {
        return $this->service()->ativar($request);
    }

    public function resetPassword(Request $request)
    {
        return $this->service()->resetPassword($request);
    }

    public function resetPasswordLink(Request $request)
    {
        return $this->service()->resetPasswordLink($request);
    }

    public function gerarAtivacaoPorEmail(Request $request)
    {
        return $this->service()->gerarAtivacaoPorEmail($request);
    }

    public function getEmailAtivacao()
    {
        return $this->service()->getEmailAtivacao();
    }

    public function getEmailConfirmacao()
    {
        return $this->service()->getEmailConfirmacao();
    }
}
