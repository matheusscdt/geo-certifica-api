<?php

namespace App\Repositories;

use App\Criteria\PastaCriteria;
use App\Models\Pasta;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class PastaRepositoryEloquent extends BaseRepository implements PastaRepository
{
    protected $fieldSearchable = [
        'nome',
        'email'
    ];

    public function model()
    {
        return Pasta::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(PastaCriteria::class));
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
