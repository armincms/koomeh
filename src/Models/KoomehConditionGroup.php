<?php

namespace Armincms\Koomeh\Models; 

class KoomehConditionGroup extends Model  
{     
	/**
	 * Query related KoomehCondition.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function conditions()
	{
		return $this->hasMany(KoomehCondition::class, 'group_id');
	} 
}
