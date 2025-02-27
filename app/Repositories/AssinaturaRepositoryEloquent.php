<?php

namespace App\Repositories;

use App\Enums\StatusDocumentoEnum;
use App\Models\Assinatura;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class AssinaturaRepositoryEloquent extends BaseRepository implements AssinaturaRepository
{
    public function model()
    {
        return Assinatura::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function obterPorDocumentoEmProcesso($documentoId): AssinaturaRepositoryEloquent
    {
        return $this->scopeQuery(function ($query) use ($documentoId) {
            return $query->whereHas('autorizacao', function ($query) use ($documentoId) {
                return $query->where('autorizado', true)->whereHas('destinatario.documento', function ($query) use ($documentoId) {
                    return $query->where('documento_id', $documentoId)->where('status_documento', StatusDocumentoEnum::EmProcesso);
                });
            });
        });
    }

    public function obterPorPrazo(): AssinaturaRepositoryEloquent
    {
        return $this->scopeQuery(function ($query) {
            return $query->whereHas('autorizacao.destinatario.documento', function ($query) {
                return $query->whereHas('configuracao', function ($query) {
                    return $query->where('data_limite_assinatura', now()->toDateString());
                })->where('status_documento', StatusDocumentoEnum::EmProcesso);
            });
        });
    }
}
