<?php

namespace Armincms\Koomeh\Models; 

class KoomehPricing extends Model  
{     
	/**
	 * Query related KoomehProperty.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function properties()
	{
		return $this->belongsToMany(KoomehPricing::class, 'koomeh_pricing_property');
	} 
}
