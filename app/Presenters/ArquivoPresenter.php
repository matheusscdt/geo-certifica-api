<?php

namespace App\Presenters;

use App\Transformers\ArquivoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class ArquivoPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new ArquivoTransformer();
    }
}
