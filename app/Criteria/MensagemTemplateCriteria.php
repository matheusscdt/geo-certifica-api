<?php

namespace App\Criteria;

use App\Traits\PerfilGestorTrait;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class MensagemTemplateCriteria implements CriteriaInterface
{
    use PerfilGestorTrait;

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('perfil_id', $this->getPerfil());
    }
}
