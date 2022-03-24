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

	/**
	 * Determin if corresponce column of today is 1.
	 * 
	 * @param  string $value 
	 * @return boolean        
	 */
    public function isAvailableForToday($value='')
    {
    	$today = strtolower(now()->format('l'));

    	return $this->{$today} === 1;
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
    	return new Collections\PricingCollection($models);
    }
}
