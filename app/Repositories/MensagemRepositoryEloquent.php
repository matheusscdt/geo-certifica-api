<?php

namespace App\Repositories;

use App\Models\Mensagem;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class MensagemRepositoryEloquent extends BaseRepository implements MensagemRepository
{
    public function model()
    {
        return Mensagem::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
