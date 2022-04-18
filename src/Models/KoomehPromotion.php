<?php

namespace Armincms\Koomeh\Models; 

use Armincms\Contract\Concerns\Configurable;
use Zareismail\Markable\HasActivation;
use Zareismail\Markable\Markable;

class KoomehPromotion extends Model  
{     
    use Configurable;
    use HasActivation;
    use Markable;
    
	/**
	 * Query related KoomehPricing.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function properties()
	{
		return $this->belongsToMany(KoomehProperty::class, 'koomeh_promotion_property');
	} 

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\PromotionCollection($models);
    }

    /**
     * Determine if the promotion is tagged.
     * 
     * @return boolean
     */
    public function isTagged()
    {
    	return boolval($this->tagged);
    }
}
