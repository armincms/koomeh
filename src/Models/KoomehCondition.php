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

	/**
	 * Query related KoomehProperty.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function properties()
	{
		return $this->belongsToMany(KoomehProperty::class, 'koomeh_condition_property');
	}
}
