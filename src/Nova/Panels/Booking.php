<?php

namespace Armincms\Koomeh\Nova\Panels;

use Armincms\Koomeh\Nova\PaymentBasis;
use Armincms\Koomeh\Nova\Pricing;
use Armincms\Koomeh\Nova\Reservation;
use Armincms\Contract\Nova\Fields;
use Laravel\Nova\Fields\BelongsTo; 
use Laravel\Nova\Fields\Currency;  
use Laravel\Nova\Fields\Number;  
use Laravel\Nova\Panel;

class Booking extends Panel
{  
    use Fields;

    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array  $fields
     * @return array
     */
    protected function prepareFields($fields)
    {
        $pricings = Pricing::newModel()->get()->map(function($pricing) { 
            $help = collect(Pricing::pricingDays())->filter(function($value, $day) use ($pricing) {
                return $pricing->{$day};
            })->values()->implode(', ');
            $resolveUsing = function($value, $resource) use ($pricing) {
                $attached = $resource->pricings->firstWhere(
                    $pricing->getKeyName(), $pricing->getKey()
                );

                return floatval(data_get($attached, 'pivot.amount', 0.00));
            };

            return $this->currencyField($pricing->name, "pricing_{$pricing->getKey()}")
                ->required()
                ->rules('required', 'min:0')
                ->min(0)
                ->help($help)
                ->fillUsing(function($request, $model, $requestAttribute) use ($pricing) {
                    $amount = floatval($request->get($requestAttribute));

                    return function() use ($model, $amount, $pricing) {
                        $model->pricings()->syncWithoutDetaching([
                            $pricing->getKey() => compact('amount'),
                        ]);
                    };
                })
                ->resolveUsing($resolveUsing)
                ->displayUsing($resolveUsing); 
        })->toArray();

    	$fields = array_merge([ 
            BelongsTo::make(__('How to book'), 'reservation', Reservation::class)
                ->required()
                ->sortable()
                ->showCreateRelationButton()
                ->withoutTrashed(), 

            BelongsTo::make(__('How to pay'), 'paymentBasis', PaymentBasis::class)
                ->required()
                ->sortable()
                ->showCreateRelationButton()
                ->withoutTrashed(), 

            Number::make(__('Minimum Of Reservation'), 'minimum_reservation')
                ->required()
                ->rules('required', 'min:0')
                ->min(0)
                ->help(__('Minimum of property reservation based on the payment type (example:1 day).'))
             
        ], $pricings, $fields);

        return parent::prepareFields($fields);
    }
}