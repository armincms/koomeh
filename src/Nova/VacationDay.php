<?php

namespace Armincms\Koomeh\Nova;
 
use Illuminate\Http\Request;    
use Laravel\Nova\Fields\ID;  
use Laravel\Nova\Fields\BelongsTo;  
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Http\Requests\NovaRequest; 

class VacationDay extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Koomeh\Models\KoomehVacationDay::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [  
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('Related Vacation'), 'vacation', Vacation::class)
                ->required()
                ->rules('required')
                ->showCreateRelationButton()
                ->withoutTrashed(),

            $this->dateField(__('Vacation Start'), 'start_date')
                ->required() 
                ->min('today')
                ->rules('required', 'unique:koomeh_vacation_days,start_date,{{resourceId}}'),

            $this->dateField(__('Vacation End'), 'end_date')
                ->required() 
                ->min('today')
                ->rules('required', 'unique:koomeh_vacation_days,end_date,{{resourceId}}'),
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
