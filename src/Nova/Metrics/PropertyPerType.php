<?php

namespace Armincms\Koomeh\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Armincms\Koomeh\Models\KoomehProperty;
use Armincms\Koomeh\Models\KoomehPropertyType;

class PropertyPerType extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $propertyTypes = KoomehPropertyType::get();

        return $this->count($request, KoomehProperty::class, 'property_type_id')
            ->label(function ($value) use ($propertyTypes) {
                if ($propertyType = $propertyTypes->find($value)) {
                    return $propertyType->name;
                }

                return "#{$value}";
            });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'koomeh-property-per-type';
    }
}
