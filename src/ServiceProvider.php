<?php

namespace Armincms\Koomeh;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;   
use Laravel\Nova\Nova as LaravelNova;
use Zareismail\Gutenberg\Gutenberg;

class ServiceProvider extends AuthServiceProvider 
{ 
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Models\KoomehAmenity::class => Policies\Amenity::class,
        Models\KoomehAmenityGroup::class => Policies\AmenityGroup::class, 
        Models\KoomehCondition::class => Policies\Condition::class, 
        Models\KoomehConditionGroup::class => Policies\ConditionGroup::class, 
        Models\KoomehPaymentBasis::class => Policies\PaymentBasis::class, 
        Models\KoomehPricing::class => Policies\Pricing::class, 
        Models\KoomehPromotion::class => Policies\Promotion::class, 
        Models\KoomehProperty::class => Policies\Property::class, 
        Models\KoomehPropertyLocality::class => Policies\PropertyLocality::class, 
        Models\KoomehPropertyType::class => Policies\PropertyType::class, 
        Models\KoomehReservation::class => Policies\Reservation::class, 
        Models\KoomehRoomType::class => Policies\RoomType::class, 
        Models\KoomehVacation::class => Policies\Vacation::class,  
        Models\KoomehVacationDay::class => Policies\VacationDay::class,  
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations'); 
        $this->registerPolicies();
        $this->conversions();
        $this->resources();
        $this->components();
        $this->fragments();
        $this->widgets();
        $this->templates();
        $this->menus();
        
        \Event::subscribe(Listeners\Order::class);
    }

    /**
     * Set media conversions for resources.
     * 
     * @return 
     */
    protected function conversions()
    {
        $this->app->afterResolving('conversion', function($manager) {
            $manager->extend('property-gallery', function() {
                return new \Armincms\Conversion\CommonConversion;
            }); 
        });
    }

    /**
     * Register the application's Nova resources.
     *
     * @return void
     */
    protected function resources()
    { 
        LaravelNova::resources([
            Nova\Amenity::class, 
            Nova\AmenityGroup::class, 
            Nova\Condition::class, 
            Nova\ConditionGroup::class, 
            Nova\Host::class, 
            Nova\PaymentBasis::class, 
            Nova\Pricing::class, 
            Nova\Promotion::class, 
            Nova\Property::class, 
            Nova\PropertyLocality::class, 
            Nova\PropertyType::class, 
            Nova\Reservation::class, 
            Nova\RoomType::class, 
            Nova\Vacation::class, 
            Nova\VacationDay::class, 
        ]);
    }

    /**
     * Register the application's Gutenberg components.
     *
     * @return void
     */
    protected function components()
    {  
        Gutenberg::components([
            // Cypress\SearchProperty::class,
        ]);
    }

    /**
     * Register the application's Gutenberg fragments.
     *
     * @return void
     */
    protected function fragments()
    {   
        Gutenberg::fragments([
            Cypress\Fragments\Property::class, 
        ]);
    }

    /**
     * Register the application's Gutenberg widgets.
     *
     * @return void
     */
    protected function widgets()
    {   
        Gutenberg::widgets([ 
            Cypress\Widgets\FilterProperty::class, 
            Cypress\Widgets\SingleProperty::class, 
        ]);
    }

    /**
     * Register the application's Gutenberg templates.
     *
     * @return void
     */
    protected function templates()
    {   
        Gutenberg::templates([ 
            \Armincms\Koomeh\Gutenberg\Templates\FilterPropertyWidget::class, 
            \Armincms\Koomeh\Gutenberg\Templates\IndexProperty::class, 
            \Armincms\Koomeh\Gutenberg\Templates\SingleProperty::class, 
        ]); 
    }

    /**
     * Register the application's menus.
     *
     * @return void
     */
    protected function menus()
    {    
        $this->app->booted(function() {  
            $menus = array_unique(array_merge((array) config('nova-menu.menu_item_types'), [ 
            ]));

            app('config')->set('nova-menu.menu_item_types', $menus);  
        }); 
    }    
}
