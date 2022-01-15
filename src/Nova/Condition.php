<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Fields\Targomaan;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;  
use Laravel\Nova\Fields\ID;    
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Fields\Textarea;  
use Laravel\Nova\Http\Requests\NovaRequest; 

class Condition extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Koomeh\Models\KoomehCondition::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make(__('Condition Group'), 'group', ConditionGroup::class)
                ->required()
                ->sortable()
                ->showCreateRelationButton()
                ->withoutTrashed(), 

            Targomaan::make([
                Text::make(__('Condition Name'), 'name')
                    ->required()
                    ->rules('required', 'max:250'), 

                Textarea::make(__('Condition Help'), 'help'),  
            ]),  
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
 
            Text::make(__('Condition Name'), 'name')->sortable(), 

            BelongsTo::make(__('Condition Group'), 'group', ConditionGroup::class)
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
