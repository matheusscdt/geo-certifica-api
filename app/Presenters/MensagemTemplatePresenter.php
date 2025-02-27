<?php

namespace App\Presenters;

use App\Transformers\MensagemTemplateTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class MensagemTemplatePresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new MensagemTemplateTransformer();
    }
}
