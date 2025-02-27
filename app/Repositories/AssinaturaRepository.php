<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface AssinaturaRepository extends RepositoryInterface
{
    public function obterPorDocumentoEmProcesso($documentoId): AssinaturaRepositoryEloquent;
    public function obterPorPrazo();
}
