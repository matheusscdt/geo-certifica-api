<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Prettus\Validator\Exceptions\ValidatorException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Mailer\Exception\UnexpectedResponseException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $exception) {
            return response()->json([
                "message" => "Verifique as informaçoes",
                "errors" => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        });

        $exceptions->render(function (TokenBlacklistedException $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
                "errors" => "Acesso não autorizado ou token inválido."
            ], Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->render(function (JWTException $exception) {
            return response()->json([
                "message" => "Acesso não autorizado ou token inválido.",
                "errors" => $exception->getCode()
            ], $exception->getCode());
        });

        $exceptions->render(function (UnauthorizedHttpException $exception) {
            return response()->json([
                "message" => "Verifique as informaçoes",
                "errors" => $exception->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->render(function (ValidatorException $exception) {
            return response()->json([
                "message" => "Verifique as informaçoes",
                "errors" => $exception->getMessageBag()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->render(function (ValueError $exception) {
            return response()->json([
                "message" => "Verifique as informaçoes",
                "errors" => "Valor inválido para o enum."
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->render(function (QueryException $exception) {
            return response()->json([
                "message" => "Verifique as informaçoes",
                "errors" => $exception->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->render(function (UnexpectedResponseException $exception) {
            return response()->json([
                "message" => "Verifique as informaçoes",
                "errors" => "Pode ser que o e-mail informado não existe. Verifique se o e-mail está correto."
            ], Response::HTTP_BAD_GATEWAY);
        });

        $exceptions->render(function (CrossReferenceException $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
                "errors" => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });

    })->create();
