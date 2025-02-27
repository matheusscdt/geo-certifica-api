<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Certificado;

class CertificadoRepositoryEloquent extends BaseRepository implements CertificadoRepository
{
    public function model()
    {
        return Certificado::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
