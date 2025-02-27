<?php

namespace App\Presenters;

use App\Transformers\DestinatarioTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class DestinatarioPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new DestinatarioTransformer();
    }
}
