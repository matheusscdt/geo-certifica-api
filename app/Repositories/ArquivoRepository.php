<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface ArquivoRepository extends RepositoryInterface
{
    public function whereRelated($related): ArquivoRepositoryEloquent;
}
