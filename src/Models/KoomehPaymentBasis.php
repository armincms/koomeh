<?php

namespace Armincms\Koomeh\Models; 

class KoomehPaymentBasis extends Model  
{      
    use HasProperties;
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [  
        'name' => 'array', 
    ]; 
}
