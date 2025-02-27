<?php

namespace App\Traits;

use App\Enums\RelatedUploadEnum;
use App\Models\Arquivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait HandleArquivoTrait
{
    public function handleArquivos(Request $request, RelatedUploadEnum $relatedUploadEnum, Model $model): void
    {
        if (!is_null($request->arquivos)) {
            $model->arquivos->each(function (Arquivo $arquivo) {
                $this->arquivoService->deletar($arquivo);
            });
            $this->uploadArquivos($request, $relatedUploadEnum, $model);
        }
    }

    public function uploadArquivos(Request $request, RelatedUploadEnum $relatedUploadEnum, Model $model): void
    {
        $this->arquivoService->upload($relatedUploadEnum, $model->id, $request);
    }
}
