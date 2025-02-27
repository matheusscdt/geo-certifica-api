<?php

namespace App\Presenters;

use App\Transformers\ConfiguracaoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class ConfiguracaoPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new ConfiguracaoTransformer();
    }
}
