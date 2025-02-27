<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\PessoaFisica;

class PessoaFisicaRepositoryEloquent extends BaseRepository implements PessoaFisicaRepository
{
    public function model()
    {
        return PessoaFisica::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
