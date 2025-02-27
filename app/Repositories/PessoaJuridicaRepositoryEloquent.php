<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\PessoaJuridica;

class PessoaJuridicaRepositoryEloquent extends BaseRepository implements PessoaJuridicaRepository
{
    public function model()
    {
        return PessoaJuridica::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
