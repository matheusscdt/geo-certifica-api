<?php

namespace App\Repositories;

use App\Criteria\AgendaCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Agenda;

class AgendaRepositoryEloquent extends BaseRepository implements AgendaRepository
{
    protected $fieldSearchable = [
        'nome',
        'email',
        'telefone',
        'cpf'
    ];

    public function model()
    {
        return Agenda::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(AgendaCriteria::class));
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
