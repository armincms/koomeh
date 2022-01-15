<?php

namespace Armincms\Koomeh\Nova\Filters;

use Armincms\Koomeh\Nova\Reservation;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Booking extends Filter
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
        return $query->whereHas('reservation', function($query) use ($value) {
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
        return Reservation::newModel()->get()->map(function($propertyType) {
            return [
                'name' => $propertyType->name,
                'value' => $propertyType->getKey(),
            ];
        });
    }
}
