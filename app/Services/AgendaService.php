<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Convite;
use App\Models\Perfil;
use App\Models\User;
use App\Presenters\AgendaPresenter;
use App\Repositories\AgendaRepository;
use App\Validators\AgendaValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class AgendaService extends ApiService
{
    public PerfilService $perfilService;
    public UserService $userService;

    public function __construct(PerfilService $perfilService, UserService $userService)
    {
        $this->perfilService = $perfilService;
        $this->userService = $userService;
    }

    protected function relations(): array
    {
        return [
            'perfilAgenda'
        ];
    }

    protected function repository(): RepositoryInterface
    {
        return app(AgendaRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(AgendaPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(AgendaValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $perfilId = getPerfilId();
        $this->perfilService->validarPerfilUserLogado($perfilId);
        return DB::transaction(function () use ($data, $perfilId) {
            $agenda = $this->create($data);
            $agenda->perfilAgenda()->create(['perfil_id' => $perfilId]);
            return $agenda;
        });

    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        $perfilId = getPerfilId();
        $this->perfilService->validarPerfilUserLogado($perfilId);
        return $this->repository()->update($data, $id)->refresh();
    }

    public function criarPorConvite(array $data, Convite $convite): ?Agenda
    {
        $agenda = $this->repository()->skipCriteria()->findWhere(['email' => $data['email']])->first();

        if (!$agenda?->existeAgendaPorPerfil($convite->perfil_id)) {
            $agenda = $this->create($data);
            $agenda->perfilAgenda()->create(['perfil_id' => $convite->perfil_id]);
            return $agenda->refresh();
        }

        return null;
    }

    public function criarAssociandoUser(array $data, $perfilId): Agenda
    {
        $agenda = $this->repository()->skipCriteria()->findWhere(['email' => $data['email']])->first();

        if (!$agenda?->existeAgendaPorPerfil($perfilId)) {
            $agenda = $this->create($data);
            $agenda->perfilAgenda()->create(['perfil_id' => $perfilId]);
        }

        return $agenda->refresh();
    }

    public function delete($id): Response
    {
        $agenda = $this->repository()->find($id);
        $this->validarExclusao($agenda);
        return DB::transaction(function () use ($agenda) {
            $agenda->perfilAgenda()->delete();
            $agenda->delete();
            return response()->noContent();
        });
    }

    public function validarExclusao(Agenda $agenda)
    {
        if (!is_null($agenda->userAgenda)) {
            throw new ValidatorException(new MessageBag([
                "agenda_id" => "A agenda não pode ser excluída pois está associada a um usuário."
            ]));
        }

        if ($agenda->destinatarios->isNotEmpty()) {
            throw new ValidatorException(new MessageBag([
                "agenda_id" => "A agenda não pode ser excluída pois está associada a um destinatário."
            ]));
        }
    }
}
