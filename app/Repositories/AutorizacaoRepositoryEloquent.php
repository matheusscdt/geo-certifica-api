<?php

namespace App\Repositories;

use App\Models\Autorizacao;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class AutorizacaoRepositoryEloquent extends BaseRepository implements AutorizacaoRepository
{
    public function model()
    {
        return Autorizacao::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
