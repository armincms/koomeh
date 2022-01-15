<?php

namespace Armincms\Koomeh\Nova\Filters;

use Armincms\Koomeh\Nova\PropertyType;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ResidenceType extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->whereHas('propertyType', function($query) use ($value) {
            return $query->whereKey($value);
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return PropertyType::newModel()->get()->map(function($propertyType) {
            return [
                'name' => $propertyType->name,
                'value' => $propertyType->getKey(),
            ];
        });
    }
}
