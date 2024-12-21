<?php

namespace App\Presenters;

use App\Transformers\ExampleTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ExamplePresenter.
 *
 * @package namespace App\Presenters;
 */
class ExamplePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ExampleTransformer();
    }
}
