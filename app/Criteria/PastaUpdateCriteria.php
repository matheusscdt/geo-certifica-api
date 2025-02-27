<?php

namespace App\Criteria;

use App\Models\Pasta;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class PastaUpdateCriteria implements CriteriaInterface
{
    protected $nomePasta;
    protected Pasta $pasta;

    public function __construct($nomePasta, Pasta $pasta)
    {
        $this->nomePasta = $nomePasta;
        $this->pasta = $pasta;
    }
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('nome', '=', $this->nomePasta)->whereHas('perfilPasta', function ($query) {
            $query->where('perfil_id', $this->pasta->perfilPasta->perfil_id)->where('pasta_id', '!=', $this->pasta->id);
        });
    }
}
