<?php

namespace App\Services;

use App\Enums\RelatedUploadEnum;
use App\Models\Arquivo;
use App\Models\Assinatura;
use App\Models\Certificado;
use App\Models\Documento;
use App\Models\RegistroAssinaturaInterna;
use App\Presenters\RegistroAssinaturaInternaPresenter;
use App\Repositories\RegistroAssinaturaInternaRepository;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\LaravelValidator;

class RegistroAssinaturaInternaService extends ApiService
{
    public PdfService $pdfService;
    public ArquivoService $arquivoService;
    public CertificadoService $certificadoService;

    public function __construct(PdfService $pdfService, ArquivoService $arquivoService, CertificadoService $certificadoService)
    {
        $this->pdfService = $pdfService;
        $this->arquivoService = $arquivoService;
        $this->certificadoService = $certificadoService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(RegistroAssinaturaInternaRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(RegistroAssinaturaInternaPresenter::class);
    }

    protected function validator(): ?LaravelValidator
    {
        return null;
    }

    public function criarRegistroAssinaturaInternaPorDocumento(Documento $documento): Documento
    {
        $perfil = $documento->perfil();
        $documento->arquivos()->each(function (Arquivo $arquivo) use ($documento, $perfil) {
            $certificado = $this->certificadoService->obterCertificadoPorPerfil($perfil->id);
            $registroAssinaturaInterna = $this->criar($documento->id, $certificado, $arquivo);
            $pdfFile = $this->gerarArquivoComRecibo($arquivo, $registroAssinaturaInterna);
            $registroAssinaturaInterna->update(['hash' => $pdfFile->hash]);
            $pdfFileAssinado = $this->certificadoService->assinarDigitalmente($certificado, $pdfFile->pdfContent);
            $this->salvarArquivoAssinado($registroAssinaturaInterna, $arquivo, $pdfFileAssinado);
        });

        return $documento->refresh();
    }

    public function gerarArquivoRegistroAssinaturaInternaPorAssinatura(RegistroAssinaturaInterna $registroAssinaturaInterna, Assinatura $assinatura)
    {
        $perfil = $assinatura->autorizacao->destinatario->documento->perfil();

        return $assinatura->arquivos()->map(function (Arquivo $arquivo) use ($registroAssinaturaInterna, $assinatura, $perfil) {
            $certificado = $this->certificadoService->obterCertificadoPorPerfil($perfil->id);
            $pdfFile = $this->gerarArquivoComRecibo($arquivo, $registroAssinaturaInterna);
            $registroAssinaturaInterna->update(['hash' => $pdfFile->hash]);
            return $this->certificadoService->assinarDigitalmente($certificado, $pdfFile->pdfContent);
        });
    }

    public function criar($documentoId, Certificado $certificado, Arquivo $arquivo): RegistroAssinaturaInterna
    {
        return $this->create([
            'documento_id' => $documentoId,
            'certificado_id' => $certificado->id,
            'arquivo_original_id' => $arquivo->id
        ]);
    }

    public function salvarArquivoAssinado(RegistroAssinaturaInterna $registroAssinaturaInterna, Arquivo $arquivo, $pdfFileAssinado)
    {
        $pdfFileAssinadoUploadedFile = $this->arquivoService->convertPdfContentToUploadedFile($pdfFileAssinado, $arquivo->concatenarAssinadoEmNome());
        return $this->arquivoService->salvar($registroAssinaturaInterna, RelatedUploadEnum::registroAssinaturaInterna, $pdfFileAssinadoUploadedFile);
    }

    public function gerarArquivoComRecibo(Arquivo $arquivo, RegistroAssinaturaInterna $registroAssinaturaInterna): object
    {
        $pdfOriginalContent = Storage::get($arquivo->getArquivoWithBucket());
        $pdfContentConverted = $this->pdfService->convertPdfToVersion14($pdfOriginalContent);
        $paginaAdicionalPdf = $this->pdfService->gerarPdfComHtml(view('assinatura.relatorio-assinaturas', [
            'registroAssinaturaInterna' => $registroAssinaturaInterna,
        ])->render());
        $pdfContent = $this->pdfService->adicionarPagina($pdfContentConverted, $paginaAdicionalPdf);
        $hash = getHashSha256fromFileContent($pdfContent);
        $pdfContent = $this->pdfService->addHashToPDF($pdfContent, $registroAssinaturaInterna->id);
        return (object)[
            'hash' => $hash,
            'pdfContent' => $pdfContent,
        ];
    }

    public function findByIdToValidador($id)
    {
        return $this->repository()->with($this->relations())->setPresenter($this->presenter())->find($id);
    }
}
