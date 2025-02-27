<?php

namespace App\Criteria;

use Illuminate\Contracts\Auth\Authenticatable;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class DocumentoCriteria implements CriteriaInterface
{
    public ?Authenticatable $userLogado;

    public function __construct()
    {
        $this->userLogado = auth()?->user();
    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->whereHas('pasta.perfilPasta', function ($query) {

            if ($this->userLogado->isPerfilAtivoPrincipal() || $this->userLogado->isGestorPerfilAtivo()) {
                return $query->where('perfil_id', getPerfilId());
            }

            return $query->where('perfil_id', getPerfilId())->whereIn('pasta_id', $this->userLogado->pastasPerfil()->pluck('id'));

        });
    }
}
