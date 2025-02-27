<?php

namespace App\Presenters;

use App\Transformers\PastaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class PastaPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new PastaTransformer();
    }
}
