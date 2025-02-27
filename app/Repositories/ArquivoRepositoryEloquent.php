<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Arquivo;

class ArquivoRepositoryEloquent extends BaseRepository implements ArquivoRepository
{
    public function model()
    {
        return Arquivo::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function whereRelated($related): ArquivoRepositoryEloquent
    {
        return $this->scopeQuery(function ($query) use ($related) {
            return $query->where('related_type', get_class($related))
                ->where('related_id', $related->id);
        });
    }
}
