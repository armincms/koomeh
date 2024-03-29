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
        $fields = KoomehConditionGroup::with('conditions')->get()->toBase()->map(function($group) {
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
            Number::make(__('Standard Capacity'), 'accommodation')
                ->default(1)
                ->min(0)
                ->required()
                ->rules('required', 'min:1')
                ->help(__('Actual property accommodation space')),

            Number::make(__('Maximum Capacity'), 'max_accommodation')
                ->default(1)
                ->min(0)
                ->required()
                ->rules('required', 'gte:accommodation')
                ->help(__('Extra property accommodation space')),

            Number::make(__('Additional cost'), 'max_accommodation_payment')
                ->default(100)
                ->min(0)
                ->required()
                ->rules('required', 'min:0', 'max:100')
                ->help(__('Additional cost per guest')), 
        ], $fields->toArray()));
    }
}