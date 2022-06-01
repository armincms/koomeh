<?php
 
namespace Armincms\Koomeh\Listeners;
 
use Armincms\Orderable\Events\OrderVerified;
 
class Order
{
    /**
     * Check new order for promotions.
     */
    public function handlePromotions($event) {
        $event->order->loadMissing('items.salable')->items->each(function($item) {
            $item->salable->properties()->attach(data_get($item->detail, 'property'), [
                'expires' => now()->addDays($item->salable->config('duration', 1)),
            ]);
        });
    } 
 
    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        return [
            OrderVerified::class => 'handlePromotions', 
        ];
    }
}