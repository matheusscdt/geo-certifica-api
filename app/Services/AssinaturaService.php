<?php

namespace App\Services;

use App\Builders\AssinaturaRequestBuilder;
use App\Enums\StatusDocumentoEnum;
use App\Mail\ComprovanteAssinaturaMail;
use App\Mail\FinalizacaoAssinaturaMail;
use App\Models\Assinatura;
use App\Models\Autorizacao;
use App\Models\Destinatario;
use App\Models\Documento;
use App\Presenters\AssinaturaPresenter;
use App\Repositories\AssinaturaRepository;
use App\Validators\AssinaturaValidator;
use App\Validators\FinalizacaoValidator;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Database\Eloquent\Collection;
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

class AssinaturaService extends ApiService
{
    public AutorizacaoService $autorizacaoService;
    public RegistroAssinaturaInternaService $registroAssinaturaInternaService;

    public function __construct(AutorizacaoService $autorizacaoService, RegistroAssinaturaInternaService $registroAssinaturaInternaService)
    {
        $this->autorizacaoService = $autorizacaoService;
        $this->registroAssinaturaInternaService = $registroAssinaturaInternaService;
    }

    public function relations(): array
    {
        return ['autorizacao.destinatario.documento.configuracao'];
    }

    protected function repository(): RepositoryInterface
    {
        return app(AssinaturaRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(AssinaturaPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(AssinaturaValidator::class);
    }

    protected function finalizacaoValidator(): LaravelValidator
    {
        return app(FinalizacaoValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);

        $assinatura = DB::transaction(function () use ($data) {
            $autorizacao = $this->autorizacaoService->criar($data['destinatario_id']);
            return $this->criar($autorizacao, $data);
        });

        return [
            'id' => $assinatura->id,
            'autorizacao_id' => $assinatura->autorizacao_id,
            'destinatario_id' => $assinatura->autorizacao->destinatario_id
        ];
    }

    public function criar(Autorizacao $autorizacao, array $data)
    {
        $this->validarAssinatura($autorizacao);
        $data['autorizacao_id'] = $autorizacao->id;
        $requestData = new AssinaturaRequestBuilder($data)->build();
        return $this->create($requestData);
    }

    public function finalizacao(Request $request, $id): array
    {
        $data = $this->validateWithValidator($request, ValidatorInterface::RULE_CREATE, $this->finalizacaoValidator());
        $assinatura = $this->find($id);

        $assinaturaFinalizada = DB::transaction(function () use ($assinatura, $data) {
            $this->autorizacaoService->validarCodigoAutorizacao($assinatura->autorizacao->destinatario, $data['codigo']);
            $this->enviarEmailComprovanteAssinatura($assinatura);
            return $this->finalizarSeTodosAssinaram($assinatura);
        });

        return [
            'id' => $assinaturaFinalizada->id,
            'destinatario_id' => $assinaturaFinalizada->autorizacao->destinatario_id,
        ];
    }

    public function getRelatorioAssinaturas()
    {
        return view('assinatura.relatorio-assinaturas');
    }

    public function getFinalizacaoAssinaturas()
    {
        $registroAssinaturaInterna = $this->registroAssinaturaInternaService->find('9e3435ce-c562-454a-9ece-5cebf7cdc074');
        return view('assinatura.relatorio-assinaturas', ['registroAssinaturaInterna' => $registroAssinaturaInterna]);
    }

    public function getRelatorioAssinaturasPdf()
    {
        $registroAssinaturaInterna = $this->registroAssinaturaInternaService->find('9e4e093d-b930-40ef-88ce-0f2a2c9acfe4');
        $assinatura = $this->find('9e4de6ca-349d-42d1-b808-115bd3f10ef7');
        $pdfs = $this->registroAssinaturaInternaService->gerarArquivoRegistroAssinaturaInternaPorAssinatura($registroAssinaturaInterna, $assinatura);

        return response($pdfs->first(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="relatorio-assinaturas.pdf"');
    }

    public function getRelatorioAssinaturasDomPdf()
    {
        $dompdf = DomPDF::loadHTML(view('assinatura.relatorio-assinaturas')->render());
        $dompdf->setPaper('A4', 'portrait'); // Set paper size to A4 and orientation to portrait
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->render();
        return $dompdf->stream('relatorio-assinaturas.pdf');
    }

    public function validarAssinatura(Autorizacao $autorizacao): void
    {
        if ($autorizacao->destinatario->documento->status_documento === StatusDocumentoEnum::Finalizado) {
            throw new ValidatorException(new MessageBag([
                'autorizacao_id' => 'Não é possível assinar um documento finalizado'
            ]));
        }

        if ($autorizacao->destinatario->documento->status_documento === StatusDocumentoEnum::Cancelado) {
            throw new ValidatorException(new MessageBag([
                'autorizacao_id' => 'Não é possível assinar um documento cancelado'
            ]));
        }
    }

    public function enviarEmailComprovanteAssinatura(Assinatura $assinatura): ?SentMessage
    {
        $comprovanteAssinaturaMail = new ComprovanteAssinaturaMail($assinatura);
        return Mail::to($assinatura->autorizacao->destinatario->agenda->email)->send($comprovanteAssinaturaMail);
    }

    public function finalizarSeTodosAssinaram(Assinatura $assinatura): Assinatura
    {
        $assinaturaFinalizada = $assinatura->documento()->todos_destinatarios_assinaram ? $this->finalizar($assinatura) : null;

        if (!is_null($assinaturaFinalizada)) {
            $this->enviarEmailFinalizacao($assinaturaFinalizada->documento());
        }

        return $assinatura->refresh();
    }

    public function finalizar(Assinatura $assinatura): Assinatura
    {
        return DB::transaction(function () use ($assinatura) {
            $documento = $this->registroAssinaturaInternaService->criarRegistroAssinaturaInternaPorDocumento($assinatura->documento());
            $documento->finalizar();
            return $assinatura;
        });
    }

    public function finalizarAssinaturasPorPrazo(): array
    {
        $assinaturas = $this->repository()->obterPorPrazo()->all();
        return $this->finalizarPorDocumento($assinaturas->first()->documento());
    }

    private function finalizarPorDocumento(Documento $documento) : array
    {
        $documento = DB::transaction(function () use ($documento) {
            return $this->registroAssinaturaInternaService->criarRegistroAssinaturaInternaPorDocumento($documento);
        });

        $this->enviarEmailFinalizacao($documento);
        $documento->finalizar();

        return [
            'documento_id' => $documento->id,
        ];
    }

    public function finalizarAssinaturasPorDocumento($documentoId): array
    {
        $assinaturas = $this->repository()->obterPorDocumentoEmProcesso($documentoId)->all();
        $this->validarFinalizacaoSePossuirAssinaturas($assinaturas);
        return $this->finalizarPorDocumento($assinaturas->first()->documento());
    }

    public function enviarEmailFinalizacao(Documento $documento)
    {
        return $documento->destinatarios->each(function (Destinatario $destinatario) {
            if ($destinatario->assinatura_realizada) {
                $finalizacaoAssinaturaMail = new FinalizacaoAssinaturaMail($destinatario);
                Mail::to($destinatario->agenda->email)->send($finalizacaoAssinaturaMail);
            }
        });
    }

    public function validarFinalizacaoSePossuirAssinaturas(Collection $assinaturas): void
    {
        if ($assinaturas->isEmpty()) {
            throw new ValidatorException(new MessageBag([
                'assinaturas' => 'Pelo menos uma assinatura deverá existir para poder finalizar.'
            ]));
        }
    }
}
