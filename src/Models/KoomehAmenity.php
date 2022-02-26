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

    /**
     * Serialize the model to pass into the client view for single item.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForWidget($request)
    { 
		return [
            'name' => $this->name,
            'help' => $this->help,
            'group'=> data_get($this, 'group.name'),
            'icon' => $this->icon,
            'value'=> data_get($this, 'pivot.value'),
        ];
	}
}
