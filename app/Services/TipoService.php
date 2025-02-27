<?php

namespace App\Services;

use App\Models\Tipo;
use App\Presenters\TipoPresenter;
use App\Repositories\TipoRepository;
use App\Traits\PerfilGestorTrait;
use App\Validators\TipoValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class TipoService extends ApiService
{
    use PerfilGestorTrait;

    public PerfilService $perfilService;

    public function __construct(PerfilService $perfilService)
    {
        $this->perfilService = $perfilService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(TipoRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(TipoPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(TipoValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $data['perfil_id'] = $this->getPerfil();
        $this->perfilService->validarPerfilUserLogado($data['perfil_id']);
        $this->validarAcaoPerfilGestor();
        $this->validarCriarTipoPorPerfil($data['perfil_id'], $data['descricao']);
        return $this->create($data);
    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        $tipo = $this->repository()->find($id);
        $this->validarEditarTipoPorPerfil($tipo, $data['descricao']);
        return $this->repository()->update($data, $id)->refresh();
    }

    public function delete($id): Response
    {
        $tipo = $this->repository()->find($id);
        $this->validarExclusao($tipo);
        $tipo->delete($id);
        return response()->noContent();
    }

    public function validarExclusao(Tipo $tipo): void
    {
        if ($tipo->destinatarios->isNotEmpty()) {
            throw new ValidatorException(new MessageBag([
                "tipo" => "Não é possível excluir um tipo que possui destinatários."
            ]));
        }
    }

    public function validarCriarTipoPorPerfil(string $perfilId, string $descricao): void
    {
        $perfil = $this->repository()
                       ->findWhere(['perfil_id' => $perfilId, 'descricao' => $descricao])
                       ->first();

        if (!is_null($perfil)) {
            throw new ValidatorException(new MessageBag([
                "descricao" => "Já existe um tipo com essa descrição para o perfil selecionado."
            ]));
        }
    }

    public function validarEditarTipoPorPerfil(Tipo $tipoAtual, string $descricao): void
    {
        $tipo = $this->repository()
                     ->findWhere([['id', '!=', $tipoAtual->id], 'perfil_id' => $tipoAtual->perfil_id, 'descricao' => $descricao])
                     ->first();

        if (!is_null($tipo)) {
            throw new ValidatorException(new MessageBag([
                "nome" => "Já existe uma pasta com esse nome para o perfil selecionado."
            ]));
        }
    }
}
