<?php

namespace App\Presenters;

use App\Transformers\TipoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TipoPresenter.
 *
 * @package namespace App\Presenters;
 */
class TipoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TipoTransformer();
    }
}
