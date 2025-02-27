<?php

namespace App\Services;

use App\Models\UserPerfil;
use App\Presenters\UserPerfilPresenter;
use App\Repositories\UserPerfilRepository;
use App\Validators\UserPerfilValidator;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class UserPerfilService extends ApiService
{
    protected PastaService $pastaService;

    public function __construct(PastaService $pastaService)
    {
        $this->pastaService = $pastaService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(UserPerfilRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(UserPerfilPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(UserPerfilValidator::class);
    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        $userPerfil = $this->repository()->find($id);
        $this->validarUpdate($userPerfil);
        $userPerfil->update($data);
        $this->criarPastas($userPerfil, $data);
        return $userPerfil->refresh();
    }

    public function criarPastas(UserPerfil $userPerfil, array $data): UserPerfil
    {
        $pastasId = $data['pastas_id'] ?? [];
        if (!empty($pastasId)) {
            $this->pastaService->validarPastasParaPerfilSelecionado($pastasId, $userPerfil->perfil_id);
            $userPerfil->pastas()->sync($pastasId);
        }
        return $userPerfil;
    }

    public function validarUpdate(UserPerfil $userPerfil): void
    {
        if ($userPerfil->perfil_id !== getPerfilId()) {
            throw new ValidatorException(new MessageBag([
                'perfil_id' => 'Perfil não corresponde ao usuário selecionado.'
            ]));
        }

        if ($userPerfil->perfil_principal) {
            throw new ValidatorException(new MessageBag([
                'perfil_principal' => 'Perfil principal não pode ser alterado.'
            ]));
        }
    }
}
