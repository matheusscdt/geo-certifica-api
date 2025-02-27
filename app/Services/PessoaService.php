<?php

namespace App\Services;

use App\Presenters\PessoaPresenter;
use App\Repositories\PessoaRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\LaravelValidator;

class PessoaService extends ApiService
{
    protected function repository(): RepositoryInterface
    {
        return app(PessoaRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(PessoaPresenter::class);
    }

    protected function validator(): ?LaravelValidator
    {
        return null;
    }
}
