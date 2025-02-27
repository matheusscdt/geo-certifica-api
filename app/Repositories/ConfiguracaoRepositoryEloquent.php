<?php

namespace App\Repositories;

use App\Models\Configuracao;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class ConfiguracaoRepositoryEloquent extends BaseRepository implements ConfiguracaoRepository
{
    protected $fieldSearchable = [
        'documento_id',
        'data_limite_assinatura',
        'lembrete_documento'
    ];

    public function model()
    {
        return Configuracao::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
