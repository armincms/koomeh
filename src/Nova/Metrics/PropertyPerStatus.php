<?php

namespace Armincms\Koomeh\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Armincms\Koomeh\Models\KoomehProperty; 

class PropertyPerStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    { 
        $statuses = KoomehProperty::statuses();

        return $this->count($request, KoomehProperty::class, 'marked_as')
            ->label(function ($value) use ($statuses) { 
                return data_get($statuses, $value, $value);
            })
            ->colors([
                'published' => 'rgb(56, 161, 105)',
                'draft' => 'rgb(229, 62, 62)',
                'pending' => 'rgb(255, 243, 130)'
            ]);
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
        return 'koomeh-property-per-status';
    }
}
