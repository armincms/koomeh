<?php

namespace Armincms\Koomeh\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Text;

class ResidencesReservation extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Armincms\\Koomeh\\ResidencesReservation';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
    ];

    /**
     * The columns that should be searched in the translation table.
     *
     * @var array
     */
    public static $searchTranslations = [
        'name'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return static::singularLabel();
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),  

            $this->translatable([
                Text::make(__('Name'), 'name')
                    ->required()
                    ->sortable()
                    ->rules('required', 'max:250'),

                Text::make(__('Help'), 'help')
                    ->sortable(),
            ]), 

            $this->toggle(__("Active"), 'active'),

            $this->toggle(__("Default"), 'default')->rules([
                function($attribute, $value, $fail) { 
                    if(intval($value) && static::newModel()->whereDefault(1)->count()) {
                        $fail(__("You can't have two default reservation"));
                    }
                }
            ]), 

            $this->toggle(__("Admin Confirmation"), 'admin_confirmation'),  

            $this->toggle(__("User Confirmation"), 'user_confirmation'), 

            $this->toggle(__("Force Payment"), 'force_payment'),  

            $this->toggle(__("Cancelable"), 'cancelable'),  

            $this->toggle(__("Force Reserve"), 'force_reserve'),     
        ];
    }

    /**
     * Get the actions available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new Actions\ImportTheReservations)->canSee(function() {
                return ! option("_residences_reservations_imported_", 0) && 
                        \Auth::guard('admin')->check();
            }), 
        ];
    }
}
