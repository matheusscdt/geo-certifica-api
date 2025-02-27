<?php

namespace App\Services;

use App\Enums\StatusDocumentoEnum;
use App\Models\Configuracao;
use App\Presenters\ConfiguracaoPresenter;
use App\Repositories\ConfiguracaoRepository;
use App\Validators\ConfiguracaoValidator;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class ConfiguracaoService extends ApiService
{
    public DocumentoService $documentoService;

    public function __construct(DocumentoService $documentoService)
    {
        $this->documentoService = $documentoService;
    }

    protected function repository(): RepositoryInterface
    {
        return app(ConfiguracaoRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(ConfiguracaoPresenter::class);
    }

    protected function validator(): LaravelValidator
    {
        return app(ConfiguracaoValidator::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        $this->documentoService->validarDocumentoPerfil($data['documento_id'], getPerfilId());
        return $this->create($data);
    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        $configuracao = $this->find($id);
        $this->validarEdicao($configuracao);
        $configuracao->update($data);
        return $configuracao->refresh();
    }

    public function validarEdicao(Configuracao $configuracao): void
    {
        if ($configuracao->documento->status_documento !== StatusDocumentoEnum::Rascunho) {
            throw new ValidatorException(new MessageBag([
                    'documento_id' => 'Não é possível editar configuração de documento com status diferente de Rascunho'
                ]
            ));
        }
    }
}
