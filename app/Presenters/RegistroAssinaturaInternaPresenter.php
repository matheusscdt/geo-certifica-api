<?php

namespace App\Presenters;

use App\Transformers\RegistroAssinaturaInternaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class RegistroAssinaturaInternaPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new RegistroAssinaturaInternaTransformer();
    }
}
