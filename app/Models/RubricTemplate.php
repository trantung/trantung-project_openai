<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Models\RubricScore;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RubricTemplate.
 *
 * @package namespace App\Models;
 */
class RubricTemplate extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    public function rubric_score(): HasMany
    {
        return $this->hasMany(RubricScore::class);
    }
}
