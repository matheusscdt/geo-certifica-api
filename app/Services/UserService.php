<?php

namespace App\Services;

use App\Mail\UserAtivacaoMail;
use App\Mail\UserConfirmacaoMail;
use App\Models\User;
use App\Models\UserAtivacao;
use App\Presenters\UserPresenter;
use App\Repositories\UserRepository;
use App\Validators\ResetPasswordLinkValidator;
use App\Validators\ResetPasswordValidator;
use App\Validators\UserValidator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class UserService extends ApiService
{
    protected function repository(): RepositoryInterface
    {
        return app(UserRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(UserPresenter::class);
    }

    protected function validator(): ?LaravelValidator
    {
        return app(UserValidator::class);
    }

    protected function resetPasswordValidator(): ?LaravelValidator
    {
        return app(ResetPasswordValidator::class);
    }

    protected function resetPasswordLinkValidator(): ?LaravelValidator
    {
        return app(ResetPasswordLinkValidator::class);
    }

    public function findAll()
    {
        $this->validarUserLogadoPerfilPrincipal();
        return parent::findAll();
    }

    public function findById($id)
    {
        $this->validarUserLogadoPerfilPrincipal();
        return parent::findById($id);
    }

    public function validarUserLogadoPerfilPrincipal(): void
    {
        if (!auth()->user()->userPerfilVinculado()?->perfil_principal && !auth()->user()->isGestorPerfilAtivo()) {
            throw new ValidatorException(new MessageBag([
                "user" => "O usuário logado não possui o perfil principal selecionado."
            ]));
        }
    }

    public function validarUserParaConvite(?User $user, string $perfilId): void
    {
        if (!is_null($user)) {
            $this->validarUserNaoDeveSerPessoaJuridica($user);
            $this->validarExisteUserParaPerfilSelecionado($user, $perfilId);
        }
    }

    public function validarExisteUserParaPerfilSelecionado(User $user, string $perfilId): void
    {
        $existeUserParaPerfilSelecionado = $user->userPerfil->where('perfil_id', $perfilId)->isNotEmpty();

        if ($existeUserParaPerfilSelecionado) {
            throw new ValidatorException(new MessageBag([
                "user" => "O usuário já está vinculado ao perfil selecionado."
            ]));
        }
    }

    public function validarUserNaoDeveSerPessoaJuridica(User $user): void
    {
        if(!is_null($user->pessoa->pessoaJuridica)) {
            throw new ValidatorException(new MessageBag([
                "user" => "O usuário não pode ser vinculado a um perfil, pois é uma pessoa jurídica."
            ]));
        }
    }

    public function ativar(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $user = $this->repository()->skipCriteria()->findWhere(['email' => $data['email']])->first();

        $this->validarCodigoAtivacao($user->userAtivacao, $data['codigo']);
        return DB::transaction(function () use ($user) {
            $user->userAtivacao->update(['data_ativacao' => now()]);
            $user->update(['ativo' => true]);
            $this->enviarEmailConfirmacao($user->refresh());
            return ['id' => $user->id];
        });
    }

    private function enviarEmailConfirmacao(User $user): void
    {
        $userConfirmacaoMail = new UserConfirmacaoMail($user->refresh());
        Mail::to($user->email)->send($userConfirmacaoMail);
    }

    public function validarCodigoAtivacao(?UserAtivacao $userAtivacao, int $codigo): void
    {
        if(!is_null($userAtivacao) && $userAtivacao->codigo !== $codigo) {
            throw new ValidatorException(new MessageBag([
                "codigo" => "O código de ativação é inválido. Verifique por favor!"
            ]));
        }
    }

    public function gerarAtivacaoPorEmail(Request $request): array
    {
        $data = $this->validateWithValidator($request, ValidatorInterface::RULE_CREATE, $this->resetPasswordLinkValidator());
        $user = $this->repository()->skipCriteria()->findWhere(['email' => $data['email']])->first();
        $this->validarUsuarioAtivo($user);
        $userAtivacao = $user->gerarCodigoAtivacao();
        $this->enviarEmailAtivacao($userAtivacao);
        return ['id' => $user->id];
    }

    public function gerarAtivacao(User $user): void
    {
        $userAtivacao = $user->gerarCodigoAtivacao();
        $this->enviarEmailAtivacao($userAtivacao);
    }

    private function enviarEmailAtivacao(UserAtivacao $userAtivacao): void
    {
        $userAtivacaoMail = new UserAtivacaoMail($userAtivacao);
        Mail::to($userAtivacao->user->email)->send($userAtivacaoMail);
    }

    public function getEmailAtivacao()
    {
        $userAtivacao = UserAtivacao::where('user_id', '9e4403e2-cd5d-4e09-a7e5-189b426d4152')->get()->first();
        return view('emails.user.ativacao', ['userAtivacao' => $userAtivacao])->render();
    }

    public function getEmailConfirmacao()
    {
        $userAtivacao = UserAtivacao::where('user_id', '9e4403e2-cd5d-4e09-a7e5-189b426d4152')->get()->first();
        return view('emails.user.confirmacao', ['user' => $userAtivacao->user])->render();
    }

    public function resetPassword(Request $request)
    {
        $data = $this->validateWithValidator($request, ValidatorInterface::RULE_CREATE, $this->resetPasswordValidator());
        $statusResetPassword = $this->resetPasswordFromToken($data);
        $this->validarStatusResetPassword($statusResetPassword);
        return ['message' => __($statusResetPassword)];
    }

    public function resetPasswordLink(Request $request)
    {
        $data = $this->validateWithValidator($request, ValidatorInterface::RULE_CREATE, $this->resetPasswordLinkValidator());
        $statusResetPasswordLink = Password::sendResetLink($data);
        $this->validarStatusResetPasswordLink($statusResetPasswordLink);
        return ['message' => __($statusResetPasswordLink)];
    }

    private function resetPasswordFromToken(array $data)
    {
        return Password::reset(
            $data,
            function (User $user) use ($data) {
                $user->forceFill([
                    'password' => $data['password']
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
    }

    private function validarStatusResetPassword(string $statusResetPassword): void
    {
        if ($statusResetPassword !== Password::PASSWORD_RESET) {
            throw new ValidatorException(new MessageBag([
                "token" => __($statusResetPassword)
            ]));
        }
    }

    private function validarStatusResetPasswordLink(string $statusResetPasswordLink): void
    {
        if ($statusResetPasswordLink !== Password::RESET_LINK_SENT) {
            throw new ValidatorException(new MessageBag([
                "email" => __($statusResetPasswordLink)
            ]));
        }
    }

    private function validarUsuarioAtivo(User $user): void
    {
        if ($user->ativo) {
            throw new ValidatorException(new MessageBag([
                "user" => "O usuário já está ativo."
            ]));
        }
    }
}
