<?php

namespace Armincms\Koomeh\Models;
     
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as LaravelModel; 
use Illuminate\Database\Eloquent\SoftDeletes; 

abstract class Model extends LaravelModel 
{    
    use HasFactory;
    use InteractsWithTargomaan;
    use SoftDeletes; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [ 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [ 
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [  
        'name' => 'array',
        'help' => 'array',
    ];  

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return 'Armincms\Koomeh\Factories\\'. class_basename(get_called_class()).'Factory';
    }   
}
