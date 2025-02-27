<?php

namespace App\Services;

use App\Presenters\PessoaFisicaPresenter;
use App\Repositories\PessoaFisicaRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\LaravelValidator;

class PessoaFisicaService extends ApiService
{
    protected function repository(): RepositoryInterface
    {
        return app(PessoaFisicaRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(PessoaFisicaPresenter::class);
    }

    protected function validator(): ?LaravelValidator
    {
        return null;
    }
}
