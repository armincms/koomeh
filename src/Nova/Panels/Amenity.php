<?php

namespace Armincms\Koomeh\Nova\Panels;

use Armincms\Koomeh\Models\KoomehAmenity; 
use Laravel\Nova\Fields\Boolean;  
use Laravel\Nova\Fields\Hidden;  
use Laravel\Nova\Fields\Number;  
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Panel;

class Amenity extends Panel
{   
    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array  $fields
     * @return array
     */
    protected function prepareFields($fields)
    {
        $fields = KoomehAmenity::get()->sortBy('field')->map(function($amenity) {
            $field = Boolean::class;

            if ($amenity->field == 'number') {
                $field = Number::class;
            } 

            if ($amenity->field == 'text') {
                $field = Text::class;
            } 

            $fillUsing = function($request, $model, $attribute, $requestAttribute) use ($amenity) {
                if ($value = $request->get($requestAttribute)) {
                    return function() use ($model, $value, $amenity) {
                        $model->amenities()->attach($amenity->getKey(), compact('value'));
                    };
                }
            };

            $resolveUsing = function($request, $resource) use ($amenity) {
                $attached = $resource->amenities->find($amenity->getKey());

                return data_get($attached, 'pivot.value');
            };

            return $field::make($amenity->name, "amenities_{$amenity->getKey()}")
                ->required($amenity->required ? true : false)
                ->rules($amenity->required ? 'required' : 'nullable')
                ->fillUsing($fillUsing)
                ->resolveUsing($resolveUsing);
        })->prepend($this->fakeField());

        return parent::prepareFields($fields);
    }

    protected function fakeField()
    {
        return Hidden::make('amenities')->fillUsing(function($request, $model) {
            return function() use ($model) {
                return $model->amenities()->sync([]);
            };
        });
    }
}