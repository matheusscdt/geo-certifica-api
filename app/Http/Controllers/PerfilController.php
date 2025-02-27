<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\PerfilService;
use Illuminate\Http\Request;

class PerfilController extends ApiController
{
    protected function service(): ApiService
    {
        return app(PerfilService::class);
    }

    public function upload(Request $request)
    {
        return $this->service()->upload($request);
    }
}
