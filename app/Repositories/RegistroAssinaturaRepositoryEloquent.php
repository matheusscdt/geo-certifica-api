<?php

namespace App\Repositories;

use App\Models\RegistroAssinatura;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class RegistroAssinaturaRepositoryEloquent extends BaseRepository implements RegistroAssinaturaRepository
{
    public function model()
    {
        return RegistroAssinatura::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
