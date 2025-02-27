<?php

namespace App\Presenters;

use App\Transformers\PerfilTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class PerfilPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new PerfilTransformer();
    }
}
