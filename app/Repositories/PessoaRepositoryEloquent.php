<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Pessoa;

class PessoaRepositoryEloquent extends BaseRepository implements PessoaRepository
{
    public function model()
    {
        return Pessoa::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
