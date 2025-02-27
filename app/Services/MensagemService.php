<?php

namespace App\Services;

use App\Presenters\MensagemPresenter;
use App\Repositories\MensagemRepository;
use App\Validators\MensagemValidator;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;

class MensagemService extends ApiService
{
    public DocumentoService $documentoService;

    public function __construct(DocumentoService $documentoService)
    {
        $this->documentoService = $documentoService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(MensagemRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(MensagemPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(MensagemValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $this->documentoService->validarDocumentoPerfil($data['documento_id'], getPerfilId());
        return $this->create($data);
    }
}
