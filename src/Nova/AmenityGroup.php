<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Fields\Targomaan;
use Illuminate\Http\Request; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Number; 
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Http\Requests\NovaRequest; 

class AmenityGroup extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Koomeh\Models\KoomehAmenityGroup::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Targomaan::make([
                Text::make(__('Amenity Group Name'), 'name')
                    ->required()
                    ->rules('required', 'max:250'), 
            ]), 

            Number::make(__('Amenity Group Order'), 'order')
                ->default(intval(static::newModel()->max('order')) + 1)
                ->required()
                ->rules('required'), 
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fieldsForIndex(Request $request)
    {
        return [ 
            ID::make(__('ID'), 'id')->sortable(),

            Text::make(__('Amenity Group Name'), 'name')->sortable(), 

            Number::make(__('Amenity Group Order'), 'order'),
        ];
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }
}
