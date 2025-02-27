<?php

namespace App\Services;

use App\Enums\StatusDocumentoEnum;
use App\Models\Destinatario;
use App\Presenters\DestinatarioPresenter;
use App\Repositories\DestinatarioRepository;
use App\Validators\DestinatarioValidator;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class DestinatarioService extends ApiService
{
    public DocumentoService $documentoService;

    public function __construct(DocumentoService $documentoService)
    {
        $this->documentoService = $documentoService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(DestinatarioRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(DestinatarioPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(DestinatarioValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $this->documentoService->validarDocumentoPerfil($data['documento_id'], getPerfilId());
        $this->documentoService->perfilService->validarPerfilAgenda($data['agenda_id'], getPerfilId());
        $this->validarCriacao($data['documento_id']);
        $this->validarDestinatarioUnico($data['documento_id'], $data['agenda_id']);
        return $this->create($data);
    }

    public function validarCriacao($documentoId): void
    {
        $documento = $this->documentoService->find($documentoId);

        if ($documento->status_documento !== StatusDocumentoEnum::Rascunho) {
            throw new ValidatorException(new MessageBag([
                    'documento_id' => 'Não é possível inserir destinatário de documento com status diferente de Rascunho'
                ]
            ));
        }
    }

    public function findByIdToAssinatura($id)
    {
        return $this->repository()->with($this->relations())->setPresenter($this->presenter())->find($id);
    }

    public function validarDestinatarioUnico($documentoId, int $agendaId): void
    {
        $destinatario = $this->repository()->findWhere([
            'documento_id' => $documentoId,
            'agenda_id' => $agendaId
        ]);

        if ($destinatario->isNotEmpty()) {
            throw new ValidatorException(new MessageBag([
                "documento_id" => "O destinatário já foi adicionado a este documento."
            ]));
        }
    }
}
