<?php

namespace App\Services;

use App\Builders\UserLoginRequestBuilder;
use App\Builders\UserLoginResponseBuilder;
use App\Presenters\UserPresenter;
use App\Repositories\UserRepository;
use App\Validators\LoginValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginService extends ApiService
{
    protected function validator(): LaravelValidator
    {
        return app(LoginValidator::class);
    }

    protected function repository(): RepositoryInterface
    {
        return app(UserRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(UserPresenter::class);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_CREATE);
        $this->validator()->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
        $token = $this->autenticar($request->all());
        $response = new UserLoginResponseBuilder($token)->build();
        return response()->json($response);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }

    public function refresh(): JsonResponse
    {
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token, [], 10);
        $response = new UserLoginResponseBuilder($newToken)->build();
        return response()->json($response);
    }

    public function autenticar(array $dados): string
    {
        $request = new UserLoginRequestBuilder($dados)->build();
        return $this->getToken($request);
    }

    public function getToken(array $credentials): string
    {
        if (!$token = auth()->attempt($credentials)) {
            throw new UnauthorizedHttpException('Bearer', 'Credenciais de acesso inválidas.');
        }

        if (!auth()->user()->ativo) {
            throw new UnauthorizedHttpException('Bearer', 'Usuário está inativo. Solicite novamente o códido de ativação.');
        }

        return $token;
    }
}
