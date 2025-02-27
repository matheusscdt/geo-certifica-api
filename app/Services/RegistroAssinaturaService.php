<?php

namespace App\Services;

use App\Presenters\RegistroAssinaturaPresenter;
use App\Repositories\RegistroAssinaturaRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\LaravelValidator;

class RegistroAssinaturaService extends ApiService
{
    protected function repository(): RepositoryInterface
    {
        return app(RegistroAssinaturaRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(RegistroAssinaturaPresenter::class);
    }

    protected function validator(): ?LaravelValidator
    {
        return null;
    }
}
