<?php

namespace Armincms\Koomeh\Models; 

class KoomehCondition extends Model  
{      
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
    	'groupName'
    ];

    /**
     * Get the group name attribute.
     * 
     * @return [type] [description]
     */
    public function getGroupNameAttribute()
    {
    	return optional($this->group)->name;
    }
    
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
