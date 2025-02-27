<?php

namespace App\Services;

use App\Builders\UserCreateRequestBuilder;
use App\Builders\UserUpdateRequestBuilder;
use App\Models\Convite;
use App\Models\User;
use App\Validators\UserPessoaFisicaValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;

class UserPessoaFisicaService extends UserService
{
    public PessoaService $pessoaService;
    public PerfilService $perfilService;
    public AgendaService $agendaService;
    public ConviteService $conviteService;
    public PastaService $pastaService;

    public function __construct(PessoaService $pessoaService, PerfilService $perfilService, AgendaService $agendaService, ConviteService $conviteService, PastaService $pastaService)
    {
        $this->pessoaService = $pessoaService;
        $this->perfilService = $perfilService;
        $this->agendaService = $agendaService;
        $this->conviteService = $conviteService;
        $this->pastaService = $pastaService;
    }

    protected function validator(): ?LaravelValidator
    {
        return app(UserPessoaFisicaValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $userRequest = new UserCreateRequestBuilder($data)->build();

        $convite = isset($data['convite_id']) ? $this->conviteService->repository()->skipCriteria()->find($data['convite_id']) : null;

        $this->conviteService->validarEmailConvite($userRequest['user']['email'], $convite);

        return DB::transaction(function () use ($userRequest, $convite) {
            $pessoa = $this->pessoaService->create($userRequest['pessoa']);
            $pessoa->pessoaFisica()->create($userRequest['pessoaFisica']);
            $perfil = $this->perfilService->create($userRequest['perfil']);

            $this->pastaService->createGeralPorPerfil($perfil->id);
            $user = $pessoa->user()->create($userRequest['user']);
            $user->createUserPerfil($perfil->id, true, true);

            $this->agendaService->criarAssociandoUser($userRequest['agenda'], $perfil->id);

            $this->vincularConvite($convite, $user);
            $this->conviteService->userService->gerarAtivacao($user->refresh());
            return ['id' => $user->refresh()->id];
        });
    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

        $userRequest = new UserUpdateRequestBuilder($data)->build();

        return DB::transaction(function () use ($id, $userRequest) {
            $user = $this->repository()->find($id);
            $user->update($userRequest['user']);
            $user->pessoa()->update($userRequest['pessoa']);
            $user->pessoa->pessoaFisica()->update($userRequest['pessoaFisica']);
            $user->userAgenda->agenda()->update($userRequest['agenda']);
            return ['id' => $user->refresh()->id];
        });
    }

    public function vincularConvite(?Convite $convite, User $user): ?Convite
    {
        if (!is_null($convite)) {
            return $this->conviteService->vincularPorConvite($convite, $user);
        }

        return null;
    }
}
