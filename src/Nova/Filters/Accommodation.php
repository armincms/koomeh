<?php

namespace Armincms\Koomeh\Nova\Filters;

use Armincms\Koomeh\Nova\RoomType;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Accommodation extends Filter
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
        return $query->whereHas('roomType', function($query) use ($value) {
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
        return RoomType::newModel()->get()->map(function($roomType) {
            return [
                'name' => $roomType->name,
                'value' => $roomType->getKey(),
            ];
        });
    }
}
