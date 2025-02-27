<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class MensagemTemplateCreateCriteria implements CriteriaInterface
{
    private $nome;
    private $perfilId;

    public function __construct($nome, $perfilId)
    {
        $this->nome = $nome;
        $this->perfilId = $perfilId;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('nome', '=', $this->nome)->where('perfil_id', $this->perfilId);
    }
}
