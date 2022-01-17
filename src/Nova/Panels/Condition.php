<?php

namespace Armincms\Koomeh\Nova\Panels;
 
use Armincms\Fields\Targomaan;
use Armincms\Koomeh\Models\KoomehConditionGroup;   
use Laravel\Nova\Fields\BooleanGroup;  
use Laravel\Nova\Fields\Number;  
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Fields\Textarea;  
use Laravel\Nova\Panel;

class Condition extends Panel
{   
    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array  $fields
     * @return array
     */
    protected function prepareFields($fields)
    {
        $fields = KoomehConditionGroup::with('conditions')->get()->map(function($group) {
            return BooleanGroup::make($group->name, "conditions[{$group->getKey()}]")
                ->options($group->conditions->pluck('name', 'id')->all())
                ->fillUsing(function() { 
                    // save with fake fields
                })
                ->resolveUsing(function($request, $resource) {
                    return $resource->conditions->keyBy->getKey()->map(function() {
                        return true;
                    });
                });
        })->merge([
            // to save conditions
            Text::make('save_conditions')
                ->canSee(function($request) {
                    return $request->isMethod('post') || $request->isMethod('put');
                })
                ->fillUsing(function($request, $model) {
                    return function() use ($request, $model) {
                        $conditions = collect($request->get('conditions'))->flatMap(function($conditions) {
                            return collect(json_decode($conditions, true))->filter()->keys()->toArray();
                        });
                        $model->conditions()->sync($conditions->all());
                    };
                }),

            Targomaan::make([
                Textarea::make(__('Other Stay Conditions'), 'condition')
                    ->nullable()
            ]), 
        ]); 

        return parent::prepareFields(array_merge([
            Number::make(__('Adult Guests'), 'adult')
                ->default(1)
                ->min(0)
                ->required()
                ->rules('required', 'min:0'),

            Number::make(__('Children Guests'), 'children')
                ->default(0)
                ->min(0)
                ->required()
                ->rules('required', 'min:0'),

            Number::make(__('Infant Guests'), 'infant')
                ->default(0)
                ->min(0)
                ->required()
                ->rules('required', 'min:0'),
        ], $fields->toArray()));
    }
}