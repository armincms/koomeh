<?php

namespace Armincms\Koomeh\Models; 

class KoomehVacation extends Model  
{      
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [  
        'name' => 'array',
        'date' => 'date',
    ];  
}
