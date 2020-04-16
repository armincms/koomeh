<?php

namespace Armincms\Koomeh\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Text;
use Davidpiesse\NovaToggle\Toggle;
use Laravel\Nova\Fields\BelongsTo;

class ResidencesPricing extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Armincms\\Koomeh\\ResidencesPricing';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'label';

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

            BelongsTo::make(__("Floor Price"), 'floorPrice', static::class)
                ->withoutTrashed()
                ->nullable()
                ->withMeta(['singularLabel' => __("Floor Price")]),

            BelongsTo::make(__("Ceiling Price"), 'ceilingPrice', static::class)
                ->withoutTrashed()
                ->nullable()
                ->withMeta(['singularLabel' => __("Ceiling Price")]),

            $this->translatable([
                Text::make(__('Label'), 'label')
                    ->sortable()
                    ->required()
                    ->rules('required', 'max:250'), 
            ]),   

            $this->toggle(__("Default"), 'default')->default(0),

            $this->toggle(__("Adaptive"), 'adaptive')->default(0), 
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
            (new Actions\ImportThePricings)->canSee(function() {
                return ! option("_residences_pricings_imported_", 0) && 
                        \Auth::guard('admin')->check();
            }), 
        ];
    }
}
