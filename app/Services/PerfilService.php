<?php

namespace App\Services;

use App\Enums\RelatedUploadEnum;
use App\Presenters\PerfilPresenter;
use App\Repositories\PerfilRepository;
use App\Validators\PerfilValidator;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class PerfilService extends ApiService
{
    public ArquivoService $arquivoService;

    public function __construct(ArquivoService $arquivoService)
    {
        $this->arquivoService = $arquivoService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(PerfilRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(PerfilPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(PerfilValidator::class);
    }

    public function upload(Request $request)
    {
        $this->validator()->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
        $data = $request->all();
        $perfilId = $data['perfil_id'] ?? getPerfilId();
        $this->validarPerfilUserLogado($perfilId);
        $perfil = $this->repository()->find($perfilId);
        if (!is_null($perfil->arquivo)) {
            $this->arquivoService->deletar($perfil->arquivo);
            $perfil->arquivo()->delete();
        }
        return $this->arquivoService->salvar($perfil, RelatedUploadEnum::perfil, $data['arquivo']);
    }

    public function validarPerfilUserLogado(?string $perfilId): void
    {
        if (!is_null($perfilId) && !Str::isUuid($perfilId)) {
            throw new ValidatorException(new MessageBag([
                "perfil_id" => "O perfil selecionado não é um UUID válido."
            ]));
        }

        $perfil = $this->repository()->skipCriteria()->find($perfilId);

        if (is_null($perfil)) {
            throw new ValidatorException(new MessageBag([
                "perfil_id" => "O perfil selecionado não foi encontrado."
            ]));
        }

        if (!auth()->user()->gestor && !auth()->user()->perfilUserLogadoValido($perfilId)) {
            throw new ValidatorException(new MessageBag([
                "perfil_id" => "O perfil selecionado não pertence ao usuário logado no sistema."
            ]));
        }

        if (!$perfil->perfil_ativo && $perfil->id == $perfilId) {
            throw new ValidatorException(new MessageBag([
                "perfil_id" => "O perfil selecionado deve ser o perfil ativo ou gestor."
            ]));
        }
    }

    public function validarPerfilAgenda(int $agendaId, string $perfilId): void
    {
        $perfil = $this->repository()->find($perfilId);
        if ($perfil->perfilAgenda->where('agenda_id', $agendaId)->isEmpty()) {
            throw new ValidatorException(new MessageBag([
                "perfil_id" => "O perfil selecionado não pertence a agenda selecionada."
            ]));
        }
    }

    public function findAll()
    {
        $limit = request()->get('limit') ?? config('repository.pagination.limit');

        return $this->repository()->whereHas('userPerfis', function ($query) {
            return $query->where('user_id', auth()->id());
        })->with($this->relations())->setPresenter($this->presenter())->paginate($limit);
    }
}
