<?php

namespace Armincms\Koomeh\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Text;
use Davidpiesse\NovaToggle\Toggle; 

class ResidencesCondition extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Armincms\\Koomeh\\ResidencesCondition';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'condition';

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
        'condition'
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

            $this->userField(),

            Text::make(__('Condition'), 'condition')
                ->sortable()
                ->required()
                ->rules('required', 'max:255'), 

            $this->toggle(__("Dedicated"), 'dedicated'),
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
            (new Actions\ImportTheConditions)->canSee(function() {
                return ! option("_residences_conditions_imported_", 0) && 
                        \Auth::guard('admin')->check();
            }), 
        ];
    }
}
