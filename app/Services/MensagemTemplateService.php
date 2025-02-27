<?php

namespace App\Services;

use App\Criteria\MensagemTemplateCreateCriteria;
use App\Criteria\MensagemTemplateUpdateCriteria;
use App\Models\MensagemTemplate;
use App\Presenters\MensagemTemplatePresenter;
use App\Repositories\MensagemTemplateRepository;
use App\Traits\PerfilGestorTrait;
use App\Validators\MensagemTemplateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class MensagemTemplateService extends ApiService
{
    use PerfilGestorTrait;
    public PerfilService $perfilService;

    public function __construct(PerfilService $perfilService)
    {
        $this->perfilService = $perfilService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(MensagemTemplateRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(MensagemTemplatePresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(MensagemTemplateValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $data['perfil_id'] = $this->getPerfil();
        $this->perfilService->validarPerfilUserLogado($data['perfil_id']);
        $this->validarAcaoPerfilGestor();
        $this->validarCriarNomePorPerfil($data['perfil_id'], $data['nome']);
        return $this->create($data);
    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        $pasta = $this->repository()->find($id);
        $this->validarEditarPastaPorPerfil($pasta, $data['nome']);
        return $this->repository()->update($data, $id)->refresh();
    }

    public function validarCriarNomePorPerfil(string $perfilId, string $nome): void
    {
        $mensagemTemplate = $this->repository()->pushCriteria(new MensagemTemplateCreateCriteria($nome, $perfilId))->first();

        if (!is_null($mensagemTemplate) && $mensagemTemplate->perfil_id == $perfilId) {
            throw new ValidatorException(new MessageBag([
                "nome" => "Já existe um template de mensagem com esse nome para o perfil selecionado."
            ]));
        }
    }

    public function validarEditarPastaPorPerfil(MensagemTemplate $mensagemTemplate, string $nome): void
    {
        $mensagemTemplate = $this->repository()->pushCriteria(new MensagemTemplateUpdateCriteria($mensagemTemplate->id, $mensagemTemplate->perfil_id, $nome))->first();

        if (!is_null($mensagemTemplate)) {
            throw new ValidatorException(new MessageBag([
                "nome" => "Já existe um template com esse nome para o perfil selecionado."
            ]));
        }
    }
}
