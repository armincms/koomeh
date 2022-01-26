<?php

namespace Armincms\Koomeh\Models; 

class KoomehVacation extends Model  
{     
	/**
	 * Query related KoomehVacationDay.
	 * 
     * @return \Illuminate\Database\Elqoeunt\Relations\HasOneOrMany
	 */
	public function vacationDays()
	{
		return $this->hasMany(KoomehVacationDay::class, 'vacation_id');
	}  

	/**
	 * Query related KoomehPricing.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function pricings()
	{
		return $this->belongsToMany(KoomehPricing::class, 'koomeh_pricing_vacation');
	} 
}
