<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface DocumentoRepository extends RepositoryInterface
{
    public function obterParaLembrete(): DocumentoRepositoryEloquent;
}
