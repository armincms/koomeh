<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Fields\Targomaan;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo; 
use Laravel\Nova\Fields\Boolean; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Number; 
use Laravel\Nova\Fields\Select;  
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Http\Requests\NovaRequest; 

class Amenity extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Koomeh\Models\KoomehAmenity::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make(__('Amenity Group'), 'group', AmenityGroup::class)
                ->required()
                ->sortable()
                ->showCreateRelationButton()
                ->withoutTrashed(),

            Select::make(__('Amenity Type'), 'field')->options([
                    'boolean'   => __('Available Detail'),
                    'number'    => __('Countable Detail'),
                    'text'      => __('Descriptive Detail'),
                ])
                ->displayUsingLabels()
                ->required()
                ->sortable()
                ->default('text'),

            Targomaan::make([
                Text::make(__('Amenity Name'), 'name')
                    ->required()
                    ->rules('required', 'max:250'), 

                Text::make(__('Amenity Help'), 'help')
                    ->nullable()
                    ->rules('max:250') 
                    ->help(__('Write some things that help users when filling the form.')),
            ]), 

            Number::make(__('Amenity Order'), 'order')
                ->default(intval(static::newModel()->max('order')) + 1)
                ->required()
                ->rules('required'),

            Text::make(__('Amenity Icon'), 'icon')->nullable(),

            Boolean::make(__('Required Amenity'), 'required')
                ->default(false)
                ->help(__('Specifies that amenity should be filled in the property form')),
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
 
            Text::make(__('Amenity Name'), 'name')->sortable(),

            Boolean::make(__('Required Amenity'), 'required'),

            Number::make(__('Amenity Order'), 'order'),

            BelongsTo::make(__('Amenity Group'), 'group', AmenityGroup::class)
                ->sortable(),
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
}
