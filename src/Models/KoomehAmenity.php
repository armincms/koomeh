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
}
