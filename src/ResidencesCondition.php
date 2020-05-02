<?php
namespace Armincms\Koomeh; 
 
use Illuminate\Database\Eloquent\SoftDeletes;  
use Illuminate\Database\Eloquent\Model;
use Armincms\Contracts\Authorizable;
use Armincms\Concerns\Authorization; 

class ResidencesCondition extends Model implements Authorizable
{
    use SoftDeletes, IntractsWithResidence, Authorization;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;    
}
