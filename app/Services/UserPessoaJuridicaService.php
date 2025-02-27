<?php

namespace App\Services;

use App\Builders\UserCreateRequestBuilder;
use App\Builders\UserUpdateRequestBuilder;
use App\Validators\UserPessoaJuridicaValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;

class UserPessoaJuridicaService extends UserService
{
    public PessoaService $pessoaService;
    public PerfilService $perfilService;
    public AgendaService $agendaService;
    public UserService $userService;
    public PastaService $pastaService;

    public function __construct(PessoaService $pessoaService, PerfilService $perfilService, AgendaService $agendaService, UserService $userService, PastaService $pastaService)
    {
        $this->pessoaService = $pessoaService;
        $this->perfilService = $perfilService;
        $this->agendaService = $agendaService;
        $this->userService = $userService;
        $this->pastaService = $pastaService;
    }

    protected function validator(): ?LaravelValidator
    {
        return app(UserPessoaJuridicaValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $userRequest = new UserCreateRequestBuilder($data)->build();

        return DB::transaction(function () use ($userRequest) {
            $pessoa = $this->pessoaService->create($userRequest['pessoa']);
            $pessoa->pessoaJuridica()->create($userRequest['pessoaJuridica']);
            $perfil = $this->perfilService->create($userRequest['perfil']);
            $user = $pessoa->user()->create($userRequest['user']);
            $user->createUserPerfil($perfil->id, true);
            $this->pastaService->createGeralPorPerfil($perfil->id);
            $this->userService->gerarAtivacao($user->refresh());
            return ['id' => $user->id];
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
            $user->pessoa->pessoaJuridica()->update($userRequest['pessoaJuridica']);
            $user->userPerfil->first()->perfil()->update($userRequest['perfil']);
            return ['id' => $user->id];
        });

    }
}
