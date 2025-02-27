<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class PastaCreateCriteria implements CriteriaInterface
{
    private $nomePasta;
    private $perfilId;

    public function __construct($nomePasta, $perfilId)
    {
        $this->nomePasta = $nomePasta;
        $this->perfilId = $perfilId;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('nome', '=', $this->nomePasta)->whereHas('perfilPasta', function ($query) {
            $query->where('perfil_id', $this->perfilId);
        });
    }
}
