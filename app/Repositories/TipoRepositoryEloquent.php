<?php

namespace App\Repositories;

use App\Criteria\TipoCriteria;
use App\Models\Tipo;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class TipoRepositoryEloquent extends BaseRepository implements TipoRepository
{
    protected $fieldSearchable = [
        'pefil_id',
        'descricao'
    ];

    public function model()
    {
        return Tipo::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(TipoCriteria::class));
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
