<?php

namespace App\Repositories;

use App\Criteria\MensagemTemplateCriteria;
use App\Models\MensagemTemplate;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class MensagemTemplateRepositoryEloquent extends BaseRepository implements MensagemTemplateRepository
{
    protected $fieldSearchable = [
        'perfil_id',
        'nome',
        'assunto',
        'mensagem'
    ];

    public function model()
    {
        return MensagemTemplate::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(MensagemTemplateCriteria::class));
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
