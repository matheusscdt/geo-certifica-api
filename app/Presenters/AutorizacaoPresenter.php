<?php

namespace App\Presenters;

use App\Transformers\AutorizacaoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class AutorizacaoPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new AutorizacaoTransformer();
    }
}
