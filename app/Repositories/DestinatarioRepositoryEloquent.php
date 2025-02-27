<?php

namespace App\Repositories;

use App\Models\Destinatario;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class DestinatarioRepositoryEloquent extends BaseRepository implements DestinatarioRepository
{
    protected $fieldSearchable = [
        'documento_id',
        'agenda_id',
        'tipo_id'
    ];

    public function model()
    {
        return Destinatario::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
