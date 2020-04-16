<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Facility\Nova\Resource;

class Facility extends Resource
{   
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Residence';

    /**
     * Related resource class
     * 
     * @return string 
     */
    public static function relatedResource(): string
    {
        return Residence::class;
    }
}
