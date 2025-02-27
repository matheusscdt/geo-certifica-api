<?php

namespace App\Presenters;

use App\Transformers\ConviteTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class ConvitePresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new ConviteTransformer();
    }
}
