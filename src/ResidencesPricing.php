<?php
namespace Armincms\Koomeh; 
 
use Illuminate\Database\Eloquent\SoftDeletes;  
use Illuminate\Database\Eloquent\Model; 

class ResidencesPricing extends Model 
{
    use SoftDeletes, IntractsWithResidence;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;    

    public function floorPrice()
    {
        return $this->belongsTo(static::class);
    }

    public function ceilingPrice()
    {
        return $this->belongsTo(static::class);
    }
}
