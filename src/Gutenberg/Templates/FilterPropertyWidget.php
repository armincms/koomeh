<?php

namespace Armincms\Koomeh\Gutenberg\Templates; 

use Zareismail\Gutenberg\Template; 
use Zareismail\Gutenberg\Variable;

class FilterPropertyWidget extends Template 
{       
    /**
     * The logical group associated with the widget.
     *
     * @var string
     */
    public static $group = 'Property';
    
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [  
            Variable::make('items', __('HTML generated of properties')), 

            Variable::make('pagination', __('HTML generated of pagination links')), 
        ];
    } 
}
