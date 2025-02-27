<?php

namespace App\Presenters;

use App\Transformers\AgendaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class AgendaPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new AgendaTransformer();
    }
}
