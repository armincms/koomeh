<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Fields\Targomaan;
use Illuminate\Http\Request;    
use Laravel\Nova\Fields\BelongsToMany;  
use Laravel\Nova\Fields\Boolean;  
use Laravel\Nova\Fields\BooleanGroup;  
use Laravel\Nova\Fields\ID;  
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Http\Requests\NovaRequest; 

class Promotion extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Koomeh\Models\KoomehPromotion::class;

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
                Text::make(__('Promotion Name'), 'name')
                    ->required()
                    ->rules('required', 'max:250'), 

                Text::make(__('Promotion Tag Label'), 'label')
                    ->required()
                    ->rules('required', 'max:250'),  

                Text::make(__('Promotion Help'), 'help')
                    ->required()
                    ->rules('required', 'max:250'),  
            ]),   

            $this->currencyField(__('Promotion Cost'))->required(),

            Text::make(__('Promotion Icon'), 'icon')->nullable(),

            Boolean::make(__('Active The Promotion'), 'marked_as')
                ->default(false),

            Boolean::make(__('Tag The Promotion'), 'tagged')
                ->default(false)
                ->help(__('Determine that promotion name should be tagged on the property.')),

            BooleanGroup::make(__('Apply promotion on'), 'config->orders')
                ->options(static::promotionOrders()),

            BelongsToMany::make(__('Properties'), 'properties', Property::class),
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

            Text::make(__('Promotion Name'), 'name')->sortable(), 

            $this->currencyField(__('Promotion Cost'))->sortable(),

            BooleanGroup::make(__('Apply promotion on'), 'config->orders')
                ->options(static::promotionOrders()),

            Boolean::make(__('Active The Promotion'), 'marked_as')->sortable(),

            Boolean::make(__('Tag The Promotion'), 'tagged')->sortable(), 
        ];
    }

    /**
     * Get the promotion orders.
     * 
     * @return array
     */
    public static function promotionOrders()
    {
        return [
            'city' => __('Increase priority when filtering by city.'),
            'type' => __('Increase priority when filtering by property type.'),
            'room' => __('Increase priority when filtering by room type.'),
            'locality' => __('Increase priority when filtering by locality.'),
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
