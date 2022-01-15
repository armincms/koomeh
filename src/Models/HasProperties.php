<?php

namespace Armincms\Koomeh\Models; 

use Illuminate\Support\Str;

trait HasProperties
{     
    /**
     * Query related KoomehProperty.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function properties()
    {
        $foreignKey = Str::after($this->getForeignKey(), 'koomeh_');

        return $this->hasMany(KoomehProperty::class, $foreignKey);
    } 
}
