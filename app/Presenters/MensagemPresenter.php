<?php

namespace App\Presenters;

use App\Transformers\MensagemTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class MensagemPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new MensagemTransformer();
    }
}
