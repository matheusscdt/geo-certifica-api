<?php

namespace App\Presenters;

use App\Transformers\UserPerfilTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class UserPerfilPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new UserPerfilTransformer();
    }
}
