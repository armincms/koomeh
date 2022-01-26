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
		return $this->belongsToMany(KoomehProperty::class, 'koomeh_pricing_property');
	} 

	/**
	 * Query related KoomehVacation.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function vacations()
	{
		return $this->belongsToMany(KoomehVacation::class, 'koomeh_pricing_vacation');
	} 
}
