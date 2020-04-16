<?php

namespace Armincms\Koomeh;
 
use Illuminate\Support\ServiceProvider; 
use Laravel\Nova\Nova as LaravelNova; 

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');  
        LaravelNova::serving([$this, 'servingNova']); 
    } 

    public function servingNova()
    {
        LaravelNova::resources([
            Nova\Residence::class,
            Nova\Facility::class,
            Nova\ResidencesType::class,
            Nova\ResidencesUsage::class,
            Nova\ResidencesCondition::class,
            Nova\ResidencesPricing::class,
            Nova\ResidencesReservation::class,
            Nova\Configuration::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
