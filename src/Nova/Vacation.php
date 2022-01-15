<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Fields\Targomaan;
use Illuminate\Http\Request;    
use Laravel\Nova\Fields\ID;  
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Http\Requests\NovaRequest; 

class Vacation extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Koomeh\Models\KoomehVacation::class;

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
                Text::make(__('Vacation Name'), 'name')
                    ->required()
                    ->rules('required', 'max:250'),  
            ]),  

            $this->dateField(__('Vacation Date'))
                ->required() 
                ->min('today')
                ->rules('required', 'unique:koomeh_vacations,date,{{resourceId}}'),
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

            Text::make(__('Vacation Name'), 'name')->sortable(), 
            
            $this->dateField(__('Vacation Date')),
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
