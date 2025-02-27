<?php

namespace App\Presenters;

use App\Transformers\CertificadoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class CertificadoPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new CertificadoTransformer();
    }
}
