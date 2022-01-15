<?php

namespace Armincms\Koomeh\Nova\Filters;

use Armincms\Koomeh\Nova\Host as HostResource;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Host extends Filter
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
        return $query->authorize($value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return HostResource::newModel()->get()->map(function($user) {
            return [
                'name' => $user->fullname(),
                'value' => $user->getKey(),
            ];
        });
    }
}
