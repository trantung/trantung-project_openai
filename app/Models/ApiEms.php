<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApiEms.
 *
 * @package namespace App\Models;
 */
class ApiEms extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ems_id', 'ems_name', 'ems_type_id'
    ];

    /**
     * 
     * @return BelongsTo
     */
    public function ems_type(): BelongsTo
    {
        return $this->belongsTo(EmsType::class);
    }

    /**
     * 
     * @return BelongsTo
     */
    public function rubric_template(): BelongsTo
    {
        return $this->belongsTo(RubricTemplate::class);
    }
}
