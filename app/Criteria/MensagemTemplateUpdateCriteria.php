<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class MensagemTemplateUpdateCriteria implements CriteriaInterface
{
    protected int $mensagemTemplateId;
    protected $perfilId;
    protected $nome;

    public function __construct(int $mensagemTemplateId, $perfilId, $nome)
    {
        $this->mensagemTemplateId = $mensagemTemplateId;
        $this->perfilId = $perfilId;
        $this->nome = $nome;
    }


    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('id', '!=', $this->mensagemTemplateId)->where('nome', '=', $this->nome)->where('perfil_id', $this->perfilId);
    }
}
