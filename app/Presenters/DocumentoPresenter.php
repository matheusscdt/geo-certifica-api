<?php

namespace App\Presenters;

use App\Transformers\DocumentoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class DocumentoPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new DocumentoTransformer();
    }
}
