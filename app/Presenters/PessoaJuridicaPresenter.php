<?php

namespace App\Presenters;

use App\Transformers\PessoaJuridicaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class PessoaJuridicaPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new PessoaJuridicaTransformer();
    }
}
