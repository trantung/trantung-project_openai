<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Example;

/**
 * Class ExampleTransformer.
 *
 * @package namespace App\Transformers;
 */
class ExampleTransformer extends TransformerAbstract
{
    /**
     * Transform the Example entity.
     *
     * @param \App\Entities\Example $model
     *
     * @return array
     */
    public function transform(Example $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
