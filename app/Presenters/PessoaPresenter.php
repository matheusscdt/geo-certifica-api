<?php

namespace App\Presenters;

use App\Transformers\PessoaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class PessoaPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new PessoaTransformer();
    }
}
