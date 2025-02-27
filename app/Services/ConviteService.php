<?php

namespace App\Services;

use App\Builders\ConviteUpdateRequestBuilder;
use App\Mail\ConviteAceitoMail;
use App\Mail\ConviteNaoAceitoMail;
use App\Models\Convite;
use App\Models\User;
use App\Presenters\ConvitePresenter;
use App\Repositories\ConviteRepository;
use App\Traits\PerfilGestorTrait;
use App\Validators\ConviteValidator;
use Illuminate\Http\Request;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class ConviteService extends ApiService
{
    use PerfilGestorTrait;

    public PerfilService $perfilService;
    public UserService $userService;
    public PastaService $pastaService;
    public AgendaService $agendaService;

    public function __construct(PerfilService $perfilService, UserService $userService, PastaService $pastaService, AgendaService $agendaService)
    {
        $this->perfilService = $perfilService;
        $this->userService = $userService;
        $this->pastaService = $pastaService;
        $this->agendaService = $agendaService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(ConviteRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(ConvitePresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(ConviteValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $data['perfil_id'] = $this->getPerfil();
        $this->perfilService->validarPerfilUserLogado($data['perfil_id']);
        $this->validarAcaoPerfilGestor();
        $user = $this->userService->repository()->skipCriteria()->findWhere(['email' => $data['email']])->first();
        $this->userService->validarUserParaConvite($user, $data['perfil_id']);
        $this->pastaService->validarPastasParaPerfilSelecionado($data['pastas_id'], $data['perfil_id']);

        $convite = DB::transaction(function () use ($data, $user) {
            return $this->criarComVinculoPerfil($data, $user);
        });

        $this->enviarEmailConvite($convite);
        return $convite;
    }

    public function criarComVinculoPerfil(array $data, ?User $user): Convite
    {
        $data['aceite'] = !is_null($user);
        $perfilId = $data['perfil_id'];

        $convite = $this->criar($data);

        if ($data['aceite']) {
            $data['data_aceite'] = now();
            $user->vincularPerfil($perfilId, false, $data['gestor']);
            $this->agendaService->criarPorConvite([
                'nome' => $user->pessoa->nome,
                'email' => $user->email,
            ], $convite);
        }

        $this->vincularPastasPorConvite($data, $user?->refresh(), $convite);
        return $convite;
    }

    public function vincularPorConvite(Convite $convite, User $user): Convite
    {
        $user->vincularPerfil($convite->perfil_id, false, $convite->gestor);
        $user->vincularPastasPorConvite($convite->perfil_id, $convite);
        $convite->update([
            'data_aceite' => now(),
            'aceite' => true
        ]);
        $this->agendaService->criarPorConvite([
            'nome' => $user->pessoa->nome,
            'email' => $user->email,
        ], $convite);
        return $convite->refresh();
    }

    private function criar(array $data): Convite
    {
        $convite = $this->criarOuAtualizar($data);
        $convite->pastas()->sync($data['pastas_id']);
        return $convite;
    }

    private function criarOuAtualizar(array $data): Convite
    {
        $convite = $this->repository()->findWhere([
            'email' => $data['email'],
            'perfil_id' => $data['perfil_id']
        ])->first();

        if (is_null($convite)) {
            return $this->create($data);
        }

        $request = new ConviteUpdateRequestBuilder($data)->build();
        $convite->update($request);
        return $convite->refresh();
    }

    private function vincularPastasPorConvite(array $data, ?User $user, Convite $convite): void
    {
        if ($data['aceite']) {
            $user->vincularPastasPorConvite($data['perfil_id'], $convite);
        }
    }

    public function validarEmailConvite(string $email, ?Convite $convite): void
    {
        if (!is_null($convite) && $convite->email !== $email) {
            throw new ValidatorException(new MessageBag([
                "email" => "O e-mail do convite deve ser o mesmo do cadastro do usuário."
            ]));
        }
    }

    private function enviarEmailConviteNaoAceito(Convite $convite): ?SentMessage
    {
        $conviteNaoAceitoMail = new ConviteNaoAceitoMail($convite);
        return Mail::to($convite->email)->send($conviteNaoAceitoMail);
    }

    private function enviarEmailConviteAceito(Convite $convite): ?SentMessage
    {
        $conviteAceitoMail = new ConviteAceitoMail($convite);
        return Mail::to($convite->email)->send($conviteAceitoMail);
    }

    public function enviarEmailConvite(Convite $convite): ?SentMessage
    {
        if (!$convite->aceite && is_null($convite->data_aceite)) {
            return $this->enviarEmailConviteNaoAceito($convite);
        }

        return $this->enviarEmailConviteAceito($convite);
    }

    public function getEmailConviteAceito()
    {
        $convite = $this->repository()->skipCriteria()->find('9e43f9c3-bfc2-43b2-99ac-b0e35bc08b09');
        return view('emails.convite.aceito', ['convite' => $convite])->render();
    }

    public function getEmailConviteNaoAceito()
    {
        $convite = $this->repository()->skipCriteria()->find('9e43f9c3-bfc2-43b2-99ac-b0e35bc08b09');
        return view('emails.convite.nao-aceito', ['convite' => $convite])->render();
    }
}
