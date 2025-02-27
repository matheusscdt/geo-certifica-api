<?php

namespace App\Services;

use App\Enums\StatusDocumentoEnum;
use App\Mail\AutorizacaoAssinaturaMail;
use App\Models\Autorizacao;
use App\Models\Destinatario;
use App\Presenters\AutorizacaoPresenter;
use App\Repositories\AutorizacaoRepository;
use App\Validators\AutorizacaoValidator;
use App\Validators\CodigoValidator;
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
use Illuminate\Support\Facades\Validator;

class AutorizacaoService extends ApiService
{
    public DestinatarioService $destinatarioService;

    public function __construct(DestinatarioService $destinatarioService)
    {
        $this->destinatarioService = $destinatarioService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(AutorizacaoRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(AutorizacaoPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(AutorizacaoValidator::class);
    }

    protected function codigoValidator(): LaravelValidator
    {
        return app(CodigoValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $autorizacao = DB::transaction(function () use ($data) {
            return $this->criar($data['destinatario_id']);
        });

        return [
            'id' => $autorizacao->id,
            'destinatario_id' => $autorizacao->destinatario_id
        ];
    }

    public function criar($destinatarioId): Autorizacao
    {
        $destinatario = $this->destinatarioService->find($destinatarioId);
        $this->validarDocumento($destinatario);
        $this->validarPossuiAssinatura($destinatario);
        $autorizacao = $this->create(['destinatario_id' => $destinatarioId]);
        $this->enviarEmailAutorizacaoAssinatura($autorizacao);
        return $autorizacao;
    }

    public function validarCodigo(Request $request): array
    {
        $data = $this->validateWithValidator($request, ValidatorInterface::RULE_CREATE, $this->codigoValidator());
        $destinatario = $this->destinatarioService->find($data['destinatario_id']);
        $autorizacao = $this->validarCodigoAutorizacao($destinatario, $data['codigo']);
        return ['id' => $autorizacao->refresh()->id];
    }

    public function gerarCodigoPorAssinatura($assinaturaId): array
    {
        $this->isValidUuid($assinaturaId);
        $autorizacao = $this->repository()->whereHas('assinatura', function ($query) use ($assinaturaId) {
            $query->where('id', $assinaturaId);
        })->first();

        $this->validarDocumento($autorizacao->destinatario);
        $autorizacao->update([
            'codigo' => gerarCodigo(),
            'data_validade' => now()->addHour(),
            'autorizado' => false
        ]);
        $this->enviarEmailAutorizacaoAssinatura($autorizacao->refresh());
        return ['id' => $autorizacao->id];
    }

    private function isValidUuid($uuid)
    {
        if (!isValidUuid($uuid)) {
            throw new ValidatorException(new MessageBag([
                "assinatura_id" => "O campo uuid é inválido."
            ]));
        }
    }

    public function validarAutorizacaoExiste(?Autorizacao $autorizacao)
    {
        if (is_null($autorizacao)) {
            throw new ValidatorException(new MessageBag([
                "autorizacao" => "Não foi encontrada uma autorização ativa para o destinatário."
            ]));
        }
    }

    public function validarCodigoAutorizacao(Destinatario $destinatario, int $codigo): Autorizacao
    {
        $autorizacao = $destinatario->autorizacaoAtivaNaoAutorizado();
        $this->validarDocumento($destinatario);
        $this->validarAutorizacao($autorizacao, $codigo);
        $autorizacao->update(['autorizado' => true]);
        return $autorizacao->refresh();
    }

    public function validarDocumento(Destinatario $destinatario): void
    {
        if ($destinatario->documento->status_documento !== StatusDocumentoEnum::EmProcesso) {
            throw new ValidatorException(new MessageBag([
                "documento_id" => "Não é possível autorizar um documento com status diferente de Em Processo."
            ]));
        }
    }

    public function validarPossuiAssinatura(Destinatario $destinatario): void
    {
        if (!is_null($destinatario->autorizacaoValida()?->assinatura)) {
            throw new ValidatorException(new MessageBag([
                "assinatura" => "O destinatário já possui assinatura efetuada para este documento."
            ]));
        }
    }

    public function validarAutorizacao(?Autorizacao $autorizacao, int $codigo): void
    {
        if (is_null($autorizacao)) {
            throw new ValidatorException(new MessageBag([
                "autorizacao" => "Não foi encontrada uma autorização ativa para o destinatário."
            ]));
        }

        if ($autorizacao->codigo != $codigo) {
            throw new ValidatorException(new MessageBag([
                "codigo" => "Código inválido."
            ]));
        }

        if ($autorizacao->autorizado) {
            throw new ValidatorException(new MessageBag([
                "autorizado" => "O destinatário já foi autorizado."
            ]));
        }

        if (!$autorizacao->dataValidadeValida()) {
            throw new ValidatorException(new MessageBag([
                "data_validade" => "O código de autorização expirou."
            ]));
        }
    }

    public function enviarEmailAutorizacaoAssinatura(Autorizacao $autorizacao): ?SentMessage
    {
        $autorizacaoAssinaturaMail = new AutorizacaoAssinaturaMail($autorizacao);
        return Mail::to($autorizacao->destinatario->agenda->email)->send($autorizacaoAssinaturaMail);
    }
}
