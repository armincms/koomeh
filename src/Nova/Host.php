<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Contract\Nova\User; 

class Host extends User
{   
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Koomeh';


    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->resource->fullname();
    }
}
