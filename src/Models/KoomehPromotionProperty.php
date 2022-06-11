<?php

namespace Armincms\Koomeh\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class KoomehPromotionProperty extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'koomeh_promotion_property';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires' => 'datetime',
    ];
}
