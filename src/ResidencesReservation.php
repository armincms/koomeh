<?php
namespace Armincms\Koomeh; 
 
use Illuminate\Database\Eloquent\SoftDeletes;  
use Illuminate\Database\Eloquent\Model; 

class ResidencesReservation extends Model
{
    use SoftDeletes, IntractsWithResidence;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;  
}
