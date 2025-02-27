<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Perfil;

class PerfilRepositoryEloquent extends BaseRepository implements PerfilRepository
{
    protected $fieldSearchable = [
        'nome'
    ];

    public function model()
    {
        return Perfil::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
