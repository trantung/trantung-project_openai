<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ApiEms.
 *
 * @package namespace App\Models;
 */
class Course extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'api_moodle_id', 'code'
    ];

    /**
     * @return BelongsTo
     */
    public function api_moodle(): BelongsTo
    {
        return $this->belongsTo(ApiMoodle::class);
    }

}
