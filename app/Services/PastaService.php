<?php

namespace App\Services;

use App\Criteria\PastaCreateCriteria;
use App\Criteria\PastaUpdateCriteria;
use App\Models\Pasta;
use App\Presenters\PastaPresenter;
use App\Repositories\PastaRepository;
use App\Traits\PerfilGestorTrait;
use App\Validators\PastaValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class PastaService extends ApiService
{
    use PerfilGestorTrait;

    public PerfilService $perfilService;

    public function __construct(PerfilService $perfilService)
    {
        $this->perfilService = $perfilService;
    }

    protected function relations(): array
    {
        return [
            'perfilPasta'
        ];
    }

    protected function repository(): RepositoryInterface
    {
        return app(PastaRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(PastaPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(PastaValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $perfilId = $this->getPerfil();
        $this->perfilService->validarPerfilUserLogado($perfilId);
        $this->validarAcaoPerfilGestor();
        $this->validarCriarPastaPorPerfil($perfilId, $data['nome']);
        return DB::transaction(function () use ($data, $perfilId) {
            $pasta = $this->create($data);
            $pasta->perfilPasta()->create(['perfil_id' => $perfilId]);
            return $pasta;
        });
    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        $pasta = $this->repository()->find($id);
        $this->validarEditarPastaPorPerfil($pasta, $data['nome']);
        return $this->repository()->update($data, $id)->refresh();
    }

    public function validarCriarPastaPorPerfil(string $perfilId, string $nomePasta): void
    {
        $pasta = $this->repository()->pushCriteria(new PastaCreateCriteria($nomePasta, $perfilId))->first();

        if (!is_null($pasta) && $pasta->perfilPasta->perfil_id == $perfilId) {
            throw new ValidatorException(new MessageBag([
                "nome" => "Já existe uma pasta com esse nome para o perfil selecionado."
            ]));
        }
    }

    public function validarEditarPastaPorPerfil(Pasta $pastaAtual, string $nomePasta): void
    {
        $pasta = $this->repository()->pushCriteria(new PastaUpdateCriteria($nomePasta, $pastaAtual))->first();

        if (!is_null($pasta)) {
            throw new ValidatorException(new MessageBag([
                "nome" => "Já existe uma pasta com esse nome para o perfil selecionado."
            ]));
        }
    }

    public function validarPastasParaPerfilSelecionado(array $pastasId, string $perfilId): void
    {
        collect($pastasId)->each(function ($pastaId) use ($perfilId) {
            $pasta = $this->repository()->skipCriteria()->find($pastaId);
            if ($pasta->perfilPasta->perfil_id != $perfilId) {
                throw new ValidatorException(new MessageBag([
                    "pastas_id" => "A pasta '{$pasta->nome}' não pertence ao perfil selecionado."
                ]));
            }
        });
    }

    public function delete($id): Response
    {
        $pasta = $this->repository()->find($id);
        $this->validarExclusao($pasta);
        return DB::transaction(function () use ($pasta) {
            $pasta->perfilPasta()->delete();
            $pasta->delete();
            return response()->noContent();
        });
    }

    public function validarExclusao(Pasta $pasta)
    {
        if ($pasta->documentos->isNotEmpty()) {
            throw new ValidatorException(new MessageBag([
                "pasta_id" => "A pasta não pode ser excluída pois está associada a um documento."
            ]));
        }
    }

    public function createGeralPorPerfil($perfilId): Pasta
    {
        $pasta = $this->create([
            'nome' => 'Geral'
        ]);

        $pasta->perfilPasta()->create([
            'perfil_id' => $perfilId
        ]);

        return $pasta;
    }

}
