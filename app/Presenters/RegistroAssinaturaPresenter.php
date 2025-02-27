<?php

namespace App\Presenters;

use App\Transformers\RegistroAssinaturaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class RegistroAssinaturaPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new RegistroAssinaturaTransformer();
    }
}
