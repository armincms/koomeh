<?php

namespace Armincms\Koomeh\Models; 

class KoomehCondition extends Model  
{      
	/**
	 * Query related KoomehConditionGroup.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function group()
	{
		return $this->belongsTo(KoomehConditionGroup::class);
	}
}
