<?php

namespace App\Repositories;

use App\Models\RegistroAssinaturaInterna;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class RegistroAssinaturaInternaRepositoryEloquent extends BaseRepository implements RegistroAssinaturaInternaRepository
{
    public function model()
    {
        return RegistroAssinaturaInterna::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
