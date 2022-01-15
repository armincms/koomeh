<?php

namespace Armincms\Koomeh\Nova\Filters;

use Armincms\Koomeh\Models\KoomehProperty;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class Status extends BooleanFilter
{ 
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
        return $query->when(array_filter($value), function($query) use ($value) {
            return $query->markIn(collect($value)->filter()->keys()->all());
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
        return array_flip(KoomehProperty::statuses());
    }
}
