<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Models\RubricScore;

/**
 * Class RubricTemplate.
 *
 * @package namespace App\Entities;
 */
class RubricTemplate extends Model implements Transformable
{
    use TransformableTrait;

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
