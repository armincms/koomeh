<?php

namespace Armincms\Koomeh\Models; 

class KoomehReservation extends Model  
{      
    use HasProperties;
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [  
        'name' => 'array',
        'help' => 'array', 
        'admin_confirmation'=> 'boolean', 
        'user_confirmation' => 'boolean', 
        'agent_confirmation'=> 'boolean', 
        'cancellable' => 'boolean', 
        'online_payment' => 'boolean', 
        'default' => 'boolean', 
    ]; 
}
