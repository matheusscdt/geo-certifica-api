<?php

namespace App\Repositories;

use App\Criteria\DocumentoCriteria;
use App\Enums\StatusDocumentoEnum;
use App\Models\Documento;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class DocumentoRepositoryEloquent extends BaseRepository implements DocumentoRepository
{
    protected $fieldSearchable = [
        'pasta_id',
        'pasta.nome',
        'destinatarios.agenda.nome',
        'destinatarios.agenda.email',
        'status_documento'
    ];

    public function model()
    {
        return Documento::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(DocumentoCriteria::class));
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function obterParaLembrete(): DocumentoRepositoryEloquent
    {
        return $this->scopeQuery(function ($query) {
            return $query->where('status_documento', StatusDocumentoEnum::EmProcesso)->whereHas('configuracao', function ($query) {
                return $query->where('data_limite_assinatura', '>=', now()->toDateString())
                    ->where('data_limite_lembrete', '>=', now()->toDateString());
            });
        });
    }
}
