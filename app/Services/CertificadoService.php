<?php

namespace App\Services;

use App\Builders\CertificadoRequestBuilder;
use App\Models\Certificado;
use App\Presenters\CertificadoPresenter;
use App\Repositories\CertificadoRepository;
use App\Traits\PerfilGestorTrait;
use App\Validators\CertificadoValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class CertificadoService extends ApiService
{
    use PerfilGestorTrait;

    public ArquivoService $arquivoService;
    public PerfilService $perfilService;
    public LacunaService $lacunaService;

    public function __construct(ArquivoService $arquivoService, PerfilService $perfilService, LacunaService $lacunaService)
    {
        $this->arquivoService = $arquivoService;
        $this->perfilService = $perfilService;
        $this->lacunaService = $lacunaService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(CertificadoRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(CertificadoPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(CertificadoValidator::class);
    }

    public function store(Request $request)
    {
        $this->validator()->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
        $data = $request->all();
        $data['perfil_id'] = $this->getPerfil();
        $this->perfilService->validarPerfilUserLogado($data['perfil_id']);
        $this->validarAcaoPerfilGestor();
        $request = $this->setRequestData($data);
        $certificado = DB::transaction(function () use ($request) {
            $certificado = $this->create($request);
            $this->desativarSelecao($certificado, $request['perfil_id']);
            return $certificado;
        });

        return ['id' => $certificado->id, 'selecionado' => $certificado->selecionado];
    }

    public function desativarSelecao(Certificado $certificado, $perfilId)
    {
        $certificados = $this->repository()->scopeQuery(function ($query) use ($certificado, $perfilId) {
            return $query->where('perfil_id', $perfilId)->whereNotIn('id', [$certificado->id]);
        })->all();
        $certificados->each->update(['selecionado' => false]);
        return $certificados;
    }

    public function selecionar($id): array
    {
        $perfilId = $this->getPerfil();
        $this->perfilService->validarPerfilUserLogado($perfilId);
        $this->validarAcaoPerfilGestor();
        $certificado = DB::transaction(function () use ($id, $perfilId) {
            $certificado = $this->edit(['selecionado' => true], $id);
            $this->desativarSelecao($certificado, $perfilId);
            return $certificado;
        });
        return ['id' => $certificado->id, 'selecionado' => $certificado->selecionado];
    }

    private function setRequestData(array $data): array
    {
        $data['cert_info'] = $this->lacunaService->getCertInfo($data['arquivo']->getContent(), $data['password']);
        return new CertificadoRequestBuilder($data)->build();
    }

    public function obterCertificadoPorPerfil($perfilId): Certificado
    {
        $certificado = $this->repository()->findWhere([
            'perfil_id' => $perfilId,
            'selecionado' => true
        ])->first();

        if (!is_null($certificado)) {
            return $certificado;
        }

        return $this->repository()->scopeQuery(function ($query) {
            return $query->where('selecionado', true)->whereHas('perfil', function ($query) {
                $query->where('proprietario', true);
            });
        })->first();
    }

    public function assinarDigitalmente(Certificado $certificado, $contentPdfFile): string
    {
        return $this->lacunaService->signature($certificado->content_file_blob, $contentPdfFile, $certificado->password_decrypted);
    }

    public function download($id)
    {
        $certificado = $this->find($id);
        return response($certificado->content_file_blob, 200)
                ->header('Content-Type', 'application/x-pkcs12')
                ->header('Content-Disposition', 'attachment; filename="'.$certificado->filename.'"');
    }

    public function validarExclusao(Certificado $certificado): void
    {
        if ($certificado->registrosAssinaturaInterna->isNotEmpty()) {
            throw new ValidatorException(new MessageBag([
                "certificado" => "Não é possível excluir um certificado que possui registros de assinatura."
            ]));
        }
    }

    public function delete($id): Response
    {
        $certificado = $this->find($id);
        $this->validarExclusao($certificado);
        $certificado->delete();
        return response()->noContent();
    }
}
