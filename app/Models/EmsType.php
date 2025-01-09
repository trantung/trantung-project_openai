<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ApiEms;

/**
 * Class EmsType.
 *
 * @package namespace App\Models;
 */
class EmsType extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id', 'type_name'
    ];

    public function api_ems(): HasMany
    {
        return $this->hasMany(ApiEms::class);
    }
}
