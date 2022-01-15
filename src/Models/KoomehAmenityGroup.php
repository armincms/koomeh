<?php

namespace Armincms\Koomeh\Models; 

class KoomehAmenityGroup extends Model  
{     
	/**
	 * Query related KoomehAmenity.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function amenities()
	{
		return $this->hasMany(KoomehAmenity::class, 'group_id');
	} 
}
