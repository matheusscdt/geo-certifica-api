<?php

namespace App\Repositories;

use App\Criteria\ConviteCriteria;
use App\Models\Convite;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class ConviteRepositoryEloquent extends BaseRepository implements ConviteRepository
{
    public function model()
    {
        return Convite::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(ConviteCriteria::class));
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
