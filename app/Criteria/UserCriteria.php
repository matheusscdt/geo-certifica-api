<?php

namespace App\Criteria;

use App\Traits\PerfilGestorTrait;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class UserCriteria implements CriteriaInterface
{
    use PerfilGestorTrait;

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->whereHas('userPerfil', function ($query) {
            $query->where('perfil_id', $this->getPerfil());
        });
    }
}
