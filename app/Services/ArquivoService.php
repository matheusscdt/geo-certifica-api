<?php

namespace App\Services;

use App\Enums\RelatedUploadEnum;
use App\Enums\StatusDocumentoEnum;
use App\Factories\ArquivoRepositoriesFactory;
use App\Models\Arquivo;
use App\Presenters\ArquivoPresenter;
use App\Repositories\ArquivoRepository;
use App\Validators\ArquivoSingleValidator;
use App\Validators\ArquivoValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;

class ArquivoService extends ApiService
{
    protected function repository(): RepositoryInterface
    {
        return app(ArquivoRepository::class);
    }

    protected function presenter(): FractalPresenter
    {
        return app(ArquivoPresenter::class);
    }

    protected function validator(): ?LaravelValidator
    {
        return app(ArquivoValidator::class);
    }

    protected function validatorSingle(): ?LaravelValidator
    {
        return app(ArquivoSingleValidator::class);
    }

    public function upload(RelatedUploadEnum $relatedUploadEnum, $relacionamentoId, Request $request): Collection
    {
        $arquivos = new Collection($request->arquivos);

        $repository = ArquivoRepositoriesFactory::make($relatedUploadEnum);

        $relacionamento = $repository->findOrFail($relacionamentoId);

        return $arquivos->map(function (UploadedFile $arquivo) use ($relatedUploadEnum, $relacionamento) {
            return $this->salvar($relacionamento, $relatedUploadEnum, $arquivo);
        });
    }

    public function uploadSingle(RelatedUploadEnum $relatedUploadEnum, $relacionamentoId, Request $request)
    {
        $this->validatorSingle()->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
        $repository = ArquivoRepositoriesFactory::make($relatedUploadEnum);
        $relacionamento = $repository->findOrFail($relacionamentoId);
        return $this->salvar($relacionamento, $relatedUploadEnum, $request->arquivo);
    }

    public function download($arquivoId)
    {
        $arquivo = $this->repository()->findOrFail($arquivoId);
        $filename = $arquivo->nome;
        $tempImage = tempnam(sys_get_temp_dir(), $filename);
        copy($arquivo->url, $tempImage);
        return response()->download($tempImage, $filename);
    }

    public function destroy($arquivoId)
    {
        $this->repository()->findOrFail($arquivoId);
        return response()->noContent();
    }

    public function getFilePathWithoutBucket($filePath): array|string
    {
        return str_replace(env('MINIO_BUCKET'), '', $filePath);
    }

    public function salvar($relacionamento, RelatedUploadEnum $relatedUploadEnum, UploadedFile $arquivo)
    {
        $filePath = $this->uploadArquivo($relatedUploadEnum, $arquivo);

        $filePathWithoutBucket = $this->getFilePathWithoutBucket($filePath);

        return $relacionamento->arquivo()->create([
            'nome' => $arquivo->getClientOriginalName(),
            'arquivo' => $filePathWithoutBucket,
            'extensao' => $this->getExtension($arquivo),
            'mime_type' => $arquivo->getClientMimeType(),
            'tamanho' => $arquivo->getSize(),
        ]);
    }

    public function convertPdfContentToUploadedFile($pdfContent, string $originalName): UploadedFile
    {
        $extension = explode('.', $originalName)[0];
        $tempPath = tempnam(sys_get_temp_dir(), $extension);
        file_put_contents($tempPath, $pdfContent);

        return new UploadedFile(
            $tempPath,
            $originalName,
            'application/pdf',
            null,
            true
        );
    }

    private function getExtension(UploadedFile $arquivo): string
    {
        return $arquivo->getClientMimeType() === 'application/x-pkcs12' ? 'pfx' : $arquivo->extension();
    }

    protected function uploadArquivo(RelatedUploadEnum $relacionamentoNome, UploadedFile $arquivo)
    {
        $ano = now()->year;
        $mes = now()->month;

        $path = env('MINIO_BUCKET')."/{$relacionamentoNome->value}/{$ano}/{$mes}";
        $filesystem = Storage::disk(env('FILESYSTEM_DRIVER'));
        $extension = $this->getExtension($arquivo);
        $filename = Str::random(40).".{$extension}";

        if (!$resultado = $filesystem->putFileAs($path, $arquivo, $filename)) {
            throw new Exception('Não foi possível gravar o arquivo');
        }

        return $resultado;
    }


    public function excluirPorRelacionamento(RelatedUploadEnum $relacionamentoNome, $relacionamentoId): void
    {
        $repository = ArquivoRepositoriesFactory::make($relacionamentoNome);
        $relacionamento = $repository->findOrFail($relacionamentoId);
        $relacionamento->arquivos->each(function (Arquivo $arquivo) {
            $this->deletar($arquivo);
        });
    }

    public function deletar(Arquivo $arquivo): ?bool
    {
        Storage::delete($arquivo->arquivo);
        return $arquivo->delete();
    }

    public function delete($id): Response
    {
        $arquivo = $this->find($id);

        if (!is_null($arquivo->documento) && $arquivo->documento->status_documento !== StatusDocumentoEnum::Rascunho) {
            throw new ValidatorException(new MessageBag([
                'arquivo_id' => 'Somente é possível excluir arquivos do documento apenas como Rascunho.'
            ]));
        }

        $this->deletar($arquivo);
        return response()->noContent();
    }

    public function listar(RelatedUploadEnum $relacionamentoNome, $relacionamentoId)
    {
        $limit = request()->get('limit') ?? config('repository.pagination.limit');
        $repository = ArquivoRepositoriesFactory::make($relacionamentoNome);
        $related = $repository->findOrFail($relacionamentoId);
        $arquivos = app(ArquivoRepository::class)->whereRelated($related)->paginate($limit);
        return $this->presenter()->present($arquivos);
    }
}
