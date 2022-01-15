<?php

namespace Armincms\Koomeh;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;   
use Laravel\Nova\Nova as LaravelNova;
use Zareismail\Gutenberg\Gutenberg;

class ServiceProvider extends AuthServiceProvider implements DeferrableProvider
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
        Models\KoomehProperty::class => Policies\Property::class, 
        Models\KoomehPropertyType::class => Policies\PropertyType::class, 
        Models\KoomehReservation::class => Policies\Reservation::class, 
        Models\KoomehRoomType::class => Policies\RoomType::class, 
        Models\KoomehVacation::class => Policies\Vacation::class,  
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
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
    }

    /**
     * Set media conversions for resources.
     * 
     * @return 
     */
    protected function conversions()
    {
        $this->app->afterResolving('conversion', function($manager) {
            $manager->extend('article', function() {
                return new \Armincms\Conversion\CommonConversion;
            });
            $manager->extend('podcast', function() {
                return new \Armincms\Conversion\CommonConversion;
            });
            $manager->extend('post', function() {
                return new \Armincms\Conversion\CommonConversion;
            });
            $manager->extend('video', function() {
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
            Nova\Property::class, 
            Nova\PropertyType::class, 
            Nova\Reservation::class, 
            Nova\RoomType::class, 
            Nova\Vacation::class, 
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
            Cypress\Blog::class,
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
            Cypress\Fragments\Article::class,
            Cypress\Fragments\Podcast::class,
            Cypress\Fragments\Post::class,
            Cypress\Fragments\Video::class,
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
            Cypress\Widgets\BlogCategory::class,
            Cypress\Widgets\BlogTag::class,
            Cypress\Widgets\SingleArticle::class,
            Cypress\Widgets\SinglePodcast::class,
            Cypress\Widgets\SinglePost::class,
            Cypress\Widgets\SingleVideo::class,
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
            \Armincms\Koomeh\Gutenberg\Templates\IndexArticle::class,
            \Armincms\Koomeh\Gutenberg\Templates\IndexPodcast::class,
            \Armincms\Koomeh\Gutenberg\Templates\IndexPost::class,
            \Armincms\Koomeh\Gutenberg\Templates\IndexVideo::class,
            \Armincms\Koomeh\Gutenberg\Templates\SingleArticle::class,
            \Armincms\Koomeh\Gutenberg\Templates\SinglePodcast::class,
            \Armincms\Koomeh\Gutenberg\Templates\SinglePost::class,
            \Armincms\Koomeh\Gutenberg\Templates\SingleVideo::class,
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
                Menus\Article::class,
                Menus\Podcast::class,
                Menus\Post::class,
                Menus\Video::class,
            ]));

            app('config')->set('nova-menu.menu_item_types', $menus);  
        }); 
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    } 

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            \Illuminate\Console\Events\ArtisanStarting::class,
            \Laravel\Nova\Events\ServingNova::class,
        ];
    } 
}
