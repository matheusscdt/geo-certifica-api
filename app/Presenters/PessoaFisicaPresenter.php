<?php

namespace App\Presenters;

use App\Transformers\PessoaFisicaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class PessoaFisicaPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new PessoaFisicaTransformer();
    }
}
