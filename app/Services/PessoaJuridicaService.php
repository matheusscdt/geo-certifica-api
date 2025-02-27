<?php

namespace App\Services;

use App\Presenters\PessoaJuridicaPresenter;
use App\Repositories\PessoaJuridicaRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\LaravelValidator;

class PessoaJuridicaService extends ApiService
{
    protected function repository(): RepositoryInterface
    {
        return app(PessoaJuridicaRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(PessoaJuridicaPresenter::class);
    }

    protected function validator(): ?LaravelValidator
    {
        return null;
    }
}
