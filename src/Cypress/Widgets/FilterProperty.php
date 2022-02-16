<?php

namespace Armincms\Koomeh\Cypress\Widgets;

use Armincms\Contract\Gutenberg\Templates\Pagination; 
use Armincms\Contract\Gutenberg\Widgets\BootstrapsTemplate; 
use Armincms\Contract\Gutenberg\Widgets\ResolvesDisplay; 
use Armincms\Koomeh\Nova\Amenity;  
use Armincms\Koomeh\Nova\Property;  
use Armincms\Koomeh\Nova\PropertyType;  
use Armincms\Koomeh\Nova\Reservation; 
use Armincms\Koomeh\Nova\RoomType; 
use Armincms\Koomeh\Gutenberg\Templates\IndexProperty; 
use Armincms\Location\Nova\City;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;   
use OptimistDigital\MultiselectField\Multiselect;
use Whitecube\NovaFlexibleContent\Flexible;
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Gutenberg\Gutenberg; 
use Zareismail\Gutenberg\GutenbergWidget; 

class FilterProperty extends GutenbergWidget
{     
    use BootstrapsTemplate;    
    use ResolvesDisplay;   

    /**
     * The logical group associated with the widget.
     *
     * @var string
     */
    public static $group = 'Property';

    /**
     * Bootstrap the resource for the given request.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @param  \Zareismail\Cypress\Layout $layout 
     * @return void                  
     */
    public function boot(CypressRequest $request, $layout)
    {   
        parent::boot($request, $layout); 

        $template = $this->bootstrapTemplate($request, $layout, $this->metaValue(Property::uriKey()));
 
        $this->displayResourceUsing(function($attributes) use ($template) {   
            return $template->gutenbergTemplate($attributes)->render();
        }, Property::class);  

        $template = $this->bootstrapTemplate($request, $layout, $this->metaValue('pagination'));
 
        $this->displayResourceUsing(function($attributes) use ($template) {   
            return $template->gutenbergTemplate($attributes)->render();
        }, 'pagination');  
    } 

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function fields($request)
    {  
        return [ 
            Select::make(__('Display Pagination By'), 'config->pagination')
                ->options(Gutenberg::cachedTemplates()->forHandler(Pagination::class)->keyBy->getKey()->map->name)  
                ->displayUsingLabels()
                ->required()
                ->rules('required'), 

            Select::make(__('Display Properties By'), 'config->'. Property::uriKey())
                ->options(Gutenberg::cachedTemplates()->forHandler(IndexProperty::class)->keyBy->getKey()->map->name)
                ->required()
                ->rules('required')
                ->displayUsingLabels(),

            Select::make(__('Sort properties by'), 'config->ordering')
                ->options([
                    'created_at' => __('Creation Date'),
                    'updated_at' => __('Update Date'),
                    'hits' => __('Number of hits'),
                ])
                ->required()
                ->rules('required')
                ->default('created_at'),

            Select::make(__('Sort properties as'), 'config->direction')
                ->options([
                    'asc' => __('Ascending'),
                    'desc' => __('Descending'), 
                ])
                ->required()
                ->rules('required')
                ->default('asc'), 

            Select::make(__('Filter by type'), 'config->propertyType')
                ->options(PropertyType::newModel()->get()->keyBy->getKey()->map->name)
                ->nullable(),

            Select::make(__('Filter by room type'), 'config->roomType')
                ->options(RoomType::newModel()->get()->keyBy->getKey()->map->name)
                ->nullable(),

            Select::make(__('Filter by reservation'), 'config->reservation')
                ->options(Reservation::newModel()->get()->keyBy->getKey()->map->name)
                ->nullable(),

            Select::make(__('Filter by city'), 'config->city')
                ->options(City::newModel()->with('state')->get()->keyBy->getKey()->map(function($city) {
                    return [
                        'id' => $city->getKey(),
                        'label' => $city->name,
                        'group' => optional($city->state)->name,
                    ];
                }))
                ->nullable()
                ->searchable()
                ->displayUsingLabels(),

            Multiselect::make(__('Display details'), 'config->details')
                ->options(Amenity::newModel()->with('group')->get()->map(function($amenity) {
                    return [
                        'label' => $amenity->name,
                        'id' => $amenity->getKey(),
                        'group' => optional($amenity->group)->name,
                    ];
                }))->saveAsJSON(),
                
            Number::make(__('Display per page'), 'config->per_page')
                ->default(1)
                ->min(1)
                ->required()
                ->rules('required', 'min:1')
                ->help(__('Number of items that should be display on each page.')),  
        ];
    } 

    /**
     * Serialize the widget fro template.
     * 
     * @return array
     */
    public function serializeForDisplay(): array
    { 
        $properties = Property::newModel()->with([
            'propertyType', 
            'media',
            'state',
            'city',
            'zone',
            'amenities' => function($query) {
                $query->whereKey((array) $this->metaValue('details'));
            }
        ])->paginate($this->metaValue('per_page'));

        return [
            'items' => $properties->getCollection()->map(function($property) {
                return $this->displayResource(
                    $property->serializeForIndexWidget($this->getRequest()),
                    Property::class,
                );
            })->implode(''),

            'pagination' => $this->displayResource($properties->toArray(), 'pagination'),
        ];
    }

    /**
     * Query related tempaltes.
     * 
     * @param  $request [description]
     * @param  $query   [description]
     * @return          [description]
     */
    public static function relatableTemplates($request, $query)
    {
        return $query->handledBy(
            \Armincms\Koomeh\Gutenberg\Templates\FilterPropertyWidget::class
        );
    }
}
