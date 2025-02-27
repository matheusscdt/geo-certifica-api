<?php

namespace App\Http\Middleware;

use App\Services\PerfilService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidarPerfil
{
    protected PerfilService $perfilService;

    public function __construct(PerfilService $perfilService)
    {
        $this->perfilService = $perfilService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $perfilId = $request->get('perfil_id') ?? getPerfilId();
        $this->perfilService->validarPerfilUserLogado($perfilId);
        return $next($request);
    }
}
