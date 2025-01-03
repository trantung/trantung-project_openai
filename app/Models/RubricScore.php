<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RubricScore.
 *
 * @package namespace App\Models;
 */
class RubricScore extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rubric_template_id', 'lms_score', 'rule_score'
    ];

    /**
     * 
     * @return BelongsTo
     */
    public function rubric_template(): BelongsTo
    {
        return $this->belongsTo(RubricTemplate::class);
    }
}
