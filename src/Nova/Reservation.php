<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Fields\Targomaan;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean; 
use Laravel\Nova\Fields\BooleanGroup; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Http\Requests\NovaRequest; 

class Reservation extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Koomeh\Models\KoomehReservation::class;

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
                Text::make(__('Property Type Name'), 'name')
                    ->required()
                    ->rules('required', 'max:250'),

                Text::make(__('Property Type Help'), 'help')
                    ->nullable()
                    ->rules('max:250') 
                    ->help(__('This will display to users to help them to choose better option.')),
            ]),

            BooleanGroup::make(__('Reservation Configurations'))
                ->options($this->reservationConfigurations())
                ->fillUsing(function($request, $model, $attribute) {
                    $values = json_decode($request->get($attribute), true);

                    collect($values)->each(function($value, $key) use ($model) {
                        $model->setAttribute($key, $value);
                    });
                })
                ->resolveUsing(function() {
                    return collect($this->reservationConfigurations())->map(function($value, $key) {
                        return boolval(data_get($this->resource, $key, false));
                    })->toArray();
                }),

            Boolean::make(__('As Default Reservation'), 'default')
                ->default(false)
                ->required('true')
                ->rules('required')
                ->help(__('Indicates that this reservation is the default of system')),
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

            Text::make(__('Property Type Name'), 'name')->sortable(),

            Text::make(__('Property Type Help'), 'help'),

            BooleanGroup::make(__('Reservation Configurations'), function() {
                return collect($this->reservationConfigurations())->map(function($value, $key) {
                    return boolval(data_get($this->resource, $key, false));
                })->toArray();
            })->options($this->reservationConfigurations()),

            Boolean::make(__('Default Reservation'), 'default'),
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

    /**
     * Get the reservation configurations.
     * 
     * @param  string $value 
     * @return array        
     */
    protected function reservationConfigurations()
    {
        return [
            'admin_confirmation' => __('Requires booking approval by the website admin'),
            'user_confirmation' => __('Requires booking approval by the owner'),
            'agent_confirmation' => __('Requires booking approval by the agent'),
            'cancellable' => __('The user can cancel the request after confirmation'),
            'online_payment' => __('The user needs to pay to register his request'), 
        ];
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount(['properties']);
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $this->properties_count ? false : parent::authorizedToDelete($request);
    }
}
