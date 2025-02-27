<?php

namespace App\Services;

use App\Dto\DashboardDocumentoDto;
use App\Enums\DashboardDocumentoPainelEnum;
use App\Enums\StatusDocumentoEnum;
use App\Mail\SolicitacaoAssinaturaMail;
use App\Models\Destinatario;
use App\Models\Documento;
use App\Presenters\DocumentoPresenter;
use App\Repositories\DocumentoRepository;
use App\Transformers\DashboardDocumentoTransformer;
use App\Validators\DocumentoEnviarValidator;
use App\Validators\DocumentoValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use League\Fractal\Manager;
use League\Fractal\Scope;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class DocumentoService extends ApiService
{
    public PerfilService $perfilService;

    public function __construct(PerfilService $perfilService)
    {
        $this->perfilService = $perfilService;
    }

    protected function relations(): array
    {
        return [
            'pasta.perfilPasta'
        ];
    }

    protected function repository(): RepositoryInterface
    {
        return app(DocumentoRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(DocumentoPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(DocumentoValidator::class);
    }

    protected function documentoEnviarValidator(): LaravelValidator
    {
        return app(DocumentoEnviarValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $this->perfilService->validarPerfilUserLogado(getPerfilId());
        $this->validarPastaObrigatoriaSemDocumento($data);
        $this->validarPastaPorPerfil(getPerfilId(), $data['pasta_id']);
        return $this->create($data);
    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        $pastaId = $data['pasta_id'] ?? null;
        $this->validarPastaPorPerfil(getPerfilId(), $pastaId);
        $this->validarPastaDocumentoPerfil($id, getPerfilId(), $pastaId);
        return $this->repository()->update($data, $id)->refresh();
    }

    public function enviar(Request $request)
    {
        $data = $this->validateWithValidator($request, ValidatorInterface::RULE_CREATE, $this->documentoEnviarValidator());
        $documento = $this->repository()->skipCriteria()->find($data['documento_id']);
        $this->validarDocumentoPerfil($documento->id, getPerfilId());
        $this->validarEnvio($documento);
        $this->enviarEmailsSolicitacaoAssinaturaPorDocumento($documento);
        $documento->update(['status_documento' => StatusDocumentoEnum::EmProcesso]);
        return $documento;
    }

    public function enviarEmailsSolicitacaoAssinaturaPorDocumento(Documento $documento): void
    {
        $documento->destinatarios->each(function (Destinatario $destinatario) {
            if (!$destinatario->assinatura_realizada) {
                $this->enviarEmailSolicitacaoAssinatura($destinatario);
            }
        });
    }

    public function enviarLembretes(): void
    {
        $documentos = $this->repository()->skipCriteria()->obterParaLembrete()->all();
        $documentos->each(function (Documento $documento) {
            $this->enviarEmailsSolicitacaoAssinaturaPorDocumento($documento);
        });
    }

    public function enviarEmailSolicitacaoAssinatura(Destinatario $destinatario): ?SentMessage
    {
        $conviteAceitoMail = new SolicitacaoAssinaturaMail($destinatario);
        return Mail::to($destinatario->agenda->email)->send($conviteAceitoMail);
    }

    public function validarEnvio(Documento $documento): void
    {
        if ($documento->status_documento == StatusDocumentoEnum::EmProcesso) {
            throw new ValidatorException(new MessageBag([
                "status_documento" => "Não é possivel enviar o documento que já está 'Em processo'."
            ]));
        }

        if ($documento->arquivos->isEmpty()) {
            throw new ValidatorException(new MessageBag([
                "arquivos" => "O documento não possui arquivos."
            ]));
        }

        if ($documento->destinatarios->isEmpty()) {
            throw new ValidatorException(new MessageBag([
                "destinatarios" => "O documento não possui destinatários."
            ]));
        }

        if (is_null($documento->mensagem)) {
            throw new ValidatorException(new MessageBag([
                "mensagem" => "O documento não possui mensagem."
            ]));
        }

        if (is_null($documento->configuracao)) {
            throw new ValidatorException(new MessageBag([
                "configuracao" => "O documento não possui configuração."
            ]));
        }
    }

    public function validarPastaDocumentoPerfil($id, string $perfilId, ?int $pastaId): void
    {
        $documento = $this->repository()->skipCriteria()->find($id);
        $perfil = $this->perfilService->find($perfilId);

        if (!is_null($pastaId) && $perfil->perfilPasta->where('pasta_id', $pastaId)->isEmpty() && $documento->pasta->perfilPasta->perfil_id != $perfilId) {
            throw new ValidatorException(new MessageBag([
                "pasta_id" => "A pasta selecionada não pertence ao perfil."
            ]));
        }
    }

    public function validarPastaObrigatoriaSemDocumento(array $data): void
    {
        if (!isset($data['documento_id']) && !isset($data['pasta_id'])) {
            throw new ValidatorException(new MessageBag([
                "pasta_id" => "A pasta é obrigatória quando não existir um documento selecionado."
            ]));
        }
    }

    public function validarPastaPorPerfil(string $perfilId, ?int $pastaId): void
    {
        $perfil = $this->perfilService->find($perfilId);

        if (!is_null($pastaId) && $perfil->perfilPasta->where('pasta_id', $pastaId)->isEmpty()) {
            throw new ValidatorException(new MessageBag([
                "pasta_id" => "A pasta selecionada não pertence ao perfil."
            ]));
        }
    }

    public function validarDocumentoPerfil($documentoId, string $perfilId): void
    {
        $documento = $this->repository()->skipCriteria()->find($documentoId);

        if ($documento->pasta->perfilPasta->perfil_id != $perfilId) {
            throw new ValidatorException(new MessageBag([
                'documento_id' => 'Documento não pertence ao perfil do usuário'
            ]));
        }
    }

    private function getDashboardTransformer(Collection $dashboards): Scope
    {
        $fractal = new Manager();
        $transformer = new DashboardDocumentoTransformer();
        $collection = $transformer->collection($dashboards, $transformer, true);
        return $fractal->createData($collection);
    }

    private function getDataDashboard(): Collection
    {
        return DashboardDocumentoPainelEnum::casesCollection()->map(function (DashboardDocumentoPainelEnum $dashboard) {
            $dashboardDocumentoDto = new DashboardDocumentoDto($dashboard);
            $documentos = $this->getDocumentosPorStatus($dashboardDocumentoDto);
            $dashboardDocumentoDto->count = $documentos->count();
            $dashboardDocumentoDto->pastaId = getSearchValue('pasta_id');
            return $dashboardDocumentoDto;
        });
    }

    private function getDocumentosPorStatus(DashboardDocumentoDto $dashboardDocumentoDto)
    {
        $query = $this->repository();

        if ($dashboardDocumentoDto->dashboardDocumentoPainelEnum == DashboardDocumentoPainelEnum::EmProcesso) {
            return $query->findWhere(['status_documento' => StatusDocumentoEnum::EmProcesso]);
        }

        if ($dashboardDocumentoDto->dashboardDocumentoPainelEnum == DashboardDocumentoPainelEnum::Cancelados) {
            return $query->findWhere(['status_documento' => StatusDocumentoEnum::Cancelado]);
        }

        if ($dashboardDocumentoDto->dashboardDocumentoPainelEnum == DashboardDocumentoPainelEnum::Finalizados) {
            return $query->findWhere(['status_documento' => StatusDocumentoEnum::Finalizado]);
        }

        return $query;
    }

    public function dashboard(): Scope
    {
        $dashboards = $this->getDataDashboard();
        return $this->getDashboardTransformer($dashboards);
    }

    public function delete($id): Response
    {
        $documento = $this->find($id);

        if ($documento->status_documento !== StatusDocumentoEnum::Rascunho) {
            throw new ValidatorException(new MessageBag([
                'documento_id' => 'Somente é possível excluir documentos apenas como Rascunho.'
            ]));
        }

        $documento->delete();
        return response()->noContent();
    }

    public function cancelar($id)
    {
        $documento = $this->repository()->find($id);

        if ($documento->status_documento === StatusDocumentoEnum::Finalizado || $documento->status_documento === StatusDocumentoEnum::Rascunho) {
            throw new ValidatorException(new MessageBag([
                "documento_id" => "Somente é possível cancelar documentos 'Em Processo'."
            ]));
        }

        $documento->cancelar();
        return $documento->refresh();
    }

    public function deleteTrash($id): Response
    {
        return DB::transaction(function () use ($id) {
            $documento = $this->repository()->scopeQuery(function ($query) use ($id) {
                return $query->where('id', $id)->onlyTrashed();
            })->first();

            $documento->arquivos()->forceDelete();
            $documento->destinatarios()->forceDelete();
            $documento->mensagem()->forceDelete();
            $documento->configuracao()->forceDelete();
            $documento->forceDelete();
            return response()->noContent();
        });
    }

    public function cleanTrash(): Response
    {
        return DB::transaction(function () {
            $documentos = $this->repository()->scopeQuery(function ($query) {
                return $query->onlyTrashed();
            })->all();

            $documentos->each(function (Documento $documento) {
                $documento->arquivos()->forceDelete();
                $documento->destinatarios()->forceDelete();
                $documento->mensagem()->forceDelete();
                $documento->configuracao()->forceDelete();
                $documento->forceDelete();
            });

            return response()->noContent();
        });
    }
}
