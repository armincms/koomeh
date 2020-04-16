<?php

namespace Armincms\Koomeh\Nova;
 
use Armincms\Nova\Resource as NovaResource;   
use Davidpiesse\NovaToggle\Toggle;

abstract class Resource extends NovaResource
{  
    /**
     * The columns that should be searched in the translation table.
     *
     * @var array
     */
    public static $searchTranslations = [
        'label'
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Residence';  

    public function toggle($label, $attribute)
    {
        return Toggle::make($label, $attribute)
                ->default(0) 
                ->trueLabel(__("Yes"))
                ->falseLabel(__("No"));
    }
}
