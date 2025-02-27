<?php

namespace App\Repositories;

use App\Models\UserPerfil;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class UserPerfilRepositoryEloquent extends BaseRepository implements UserPerfilRepository
{
    public function model()
    {
        return UserPerfil::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
