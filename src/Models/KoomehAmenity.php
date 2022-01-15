<?php

namespace Armincms\Koomeh\Models; 

class KoomehAmenity extends Model  
{      
	/**
	 * Query related KoomehAmenityGroup.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function group()
	{
		return $this->belongsTo(KoomehAmenityGroup::class);
	}

	/**
	 * Query related KoomehProperty.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function properties()
	{
		return $this->belongsToMany(KoomehProperty::class, 'koomeh_amenity_property');
	}
}
