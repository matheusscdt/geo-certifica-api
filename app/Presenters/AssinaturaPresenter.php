<?php

namespace App\Presenters;

use App\Transformers\AssinaturaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AssinaturaPresenter.
 *
 * @package namespace App\Presenters;
 */
class AssinaturaPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AssinaturaTransformer();
    }
}
