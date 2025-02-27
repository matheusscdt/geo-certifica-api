<?php

namespace App\Repositories;

use App\Criteria\UserCriteria;
use App\Models\User;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    protected $fieldSearchable = [
        'pessoa.nome',
        'email',
        'ativo'
    ];

    public function model()
    {
        return User::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(UserCriteria::class));
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
