<?php

namespace Armincms\Koomeh\Nova\Panels;

use Alvinhu\ChildSelect\ChildSelect;
use Armincms\Fields\Targomaan;
use Armincms\Location\Nova\City;
use Armincms\Location\Nova\State;
use Armincms\Location\Nova\Zone; 
use GeneaLabs\NovaMapMarkerField\MapMarker; 
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Address extends Panel
{  
    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array  $fields
     * @return array
     */
    protected function prepareFields($fields)
    {
    	$fields = array_merge([
            Select::make(__('State'), 'state_id')->options(function() {
                return State::newModel()->get()->keyBy->getKey()->map->name->sort()->all();
            })
            ->required()
            ->rules('required')
            ->onlyOnForms(), 

            ChildSelect::make(__('City'), 'city_id')->options(function ($state) {  
                return City::newModel()->whereHas('state', function($query) use ($state) {
                    $query->whereKey($state);
                })->get()->keyBy->getKey()->map->name->sort()->all();
            })
            ->parent('state_id')
            ->required()
            ->rules('required')
            ->onlyOnForms(),

            ChildSelect::make(__('Property zone'), 'zone_id')->options(function ($city) { 
                return Zone::newModel()->whereHas('city', function($query) use ($city) {
                    $query->whereKey($city);
                })->get()->keyBy->getKey()->map->name->sort()->all();
            })
            ->parent('city_id')
            ->required()
            ->rules('required')
            ->onlyOnForms(),

            BelongsTo::make(__('State'), 'state', State::class)
                ->onlyOnDetail(),

            BelongsTo::make(__('City'), 'city', City::class)
                ->onlyOnDetail(),

            BelongsTo::make(__('Zone'), 'zone', Zone::class)
                ->onlyOnDetail(),

            Targomaan::make([
                Text::make(__('Property Address'), 'address')
                    ->nullable()
                    ->rules('nullable', 'max:250'),
            ]), 

            MapMarker::make(__('Google Location'), 'location')
                ->defaultZoom(16)
                ->defaultLatitude(29.609043426461)
                ->defaultLongitude(52.519180021444)
                ->latitude('lat')
                ->longitude('long')
                ->centerCircle(10, 'red', 1, .5),
        ], $fields);

        return parent::prepareFields($fields);
    }
}