<?php

namespace Armincms\Koomeh\Gutenberg\Templates; 

use Zareismail\Gutenberg\Template; 
use Zareismail\Gutenberg\Variable;
use Armincms\Koomeh\Models\KoomehProperty;

class IndexProperty extends Template 
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
        $conversions = (new KoomehProperty)->conversions()->implode(',');

        return [  
            Variable::make('id', __('Property Id')),

            Variable::make('name', __('Property Name')),

            Variable::make('code', __('Property Code')),

            Variable::make('url', __('Property URL')),

            Variable::make('images', __(
                "Property gallery image list. available conversions is:[{$conversions}]"
            )),

            Variable::make('hits', __('Property Hits')),

            Variable::make('propertyType.name', __('Property Type Name')),

            Variable::make('propertyType.icon', __('Property Type Icon')),

            Variable::make('propertyType.help', __('Property Type Help')),

            Variable::make('stateName', __('Property State Name')), 

            Variable::make('cityName', __('Property City Name')), 

            Variable::make('zoneName', __('Property Zone Name')),  

            Variable::make('details', __(
                'Property available amenities list. each amenity has a name, help, value, group and icon.'
            )),  

            Variable::make('creation_date', __('Property Creation Date')),

            Variable::make('last_update', __('Property Update Date')), 

            Variable::make('summary', __('Property Summary')), 
        ];
    } 
}
