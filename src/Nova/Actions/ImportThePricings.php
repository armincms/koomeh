<?php

namespace Armincms\Koomeh\Nova\Actions;
 
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;  
use Armincms\Tools\ToolbarAction\Action; 
use Armincms\Koomeh\ResidencesPricing;
use Illuminate\Http\Request;
use Laravel\Nova\Nova;

class ImportThePricings extends Action
{  
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    { 
        $pricings = ResidencesPricing::get(); 
        
        $insertions = Collection::make($this->pricings())->reject(function($pricing) use ($pricings) {
            return $pricings->pluck('label')->contains($pricing);
        });

        ResidencesPricing::insert($insertions->map(function($pricing, $index) { 
            return [ 
                'label' => $pricing,
                'default' => $pricing == 'پایه', 
                'adaptive' => $pricing == 'توافقی',  
            ];
        })->all());  

        option()->put("_residences_pricings_imported_", 1);
        
        return static::redirect(
            url(Nova::path().'/resources/residences-pricings')
        );
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [ 
        ];
    } 

    public function pricings()
    {
        return [ 
            "توافقی", 
            "پایه", 
            "اعیاد",
            "تعطیلات", 
        ];
    }
}
