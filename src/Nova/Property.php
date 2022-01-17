<?php

namespace Armincms\Koomeh\Nova;

use Alvinhu\ChildSelect\ChildSelect;
use Armincms\Fields\Targomaan;
use Armincms\Location\Nova\City;
use Armincms\Location\Nova\State;
use Armincms\Location\Nova\Zone;
use Illuminate\Http\Request;
use GeneaLabs\NovaMapMarkerField\MapMarker; 
use Laravel\Nova\Fields\Badge; 
use Laravel\Nova\Fields\BelongsTo; 
use Laravel\Nova\Fields\Boolean; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Number; 
use Laravel\Nova\Fields\Select;  
use Laravel\Nova\Fields\Stack;  
use Laravel\Nova\Fields\Text;  
use Laravel\Nova\Fields\Textarea;  
use Laravel\Nova\Panel;  
use Laravel\Nova\Http\Requests\NovaRequest;  

class Property extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Koomeh\Models\KoomehProperty::class;

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [ 'auth', 'propertyType', 'roomType' ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Select::make(__('Residence Publish Status'), 'marked_as')
                ->options(static::statuses())
                ->required()
                ->rules('required', 'in:draft,pending,published')
                ->default('draft')
                ->displayUsingLabels(),

            BelongsTo::make(__('Residence Host'), 'auth', Host::class)
                ->required()
                ->sortable()
                ->showCreateRelationButton()
                ->withoutTrashed(), 

            BelongsTo::make(__('Residence Type'), 'propertyType', PropertyType::class)
                ->required()
                ->sortable()
                ->showCreateRelationButton()
                ->withoutTrashed(), 

            BelongsTo::make(__('Accommodation'), 'roomType', RoomType::class)
                ->required()
                ->sortable()
                ->showCreateRelationButton()
                ->withoutTrashed(), 

            Targomaan::make([
                Text::make(__('Property Name'), 'name')
                    ->required()
                    ->rules('required', 'max:250'),  
            ]), 

            Text::make(__('Property Code'), 'code')
                ->help(__('Leave blank to auto generate.'))
                ->rules('nullable', 'unique:koomeh_properties,code,{{resourceId}}')
                ->fillUsing(function($request, $model, $requestAttribute, $attribute) { 
                    $model->forceFill([
                        $attribute => $request->get($requestAttribute) ?? static::generateNewCode()
                    ]);
                }),

            Targomaan::make([ 
                Textarea::make(__('Describe your property'), 'summary')
                    ->nullable()
                    ->rules('max:250'), 
            ]),

            $this->medialibrary(__('Property Gallery'), 'gallery'),

            Panels\Booking::make(__('Booking')), 

            Panels\Address::make(__('Property Address')),

            Panels\Condition::make(__('Stay Conditions')),

            Panels\Amenity::make(__('Property Amenities')),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fieldsForIndex(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
 
            Stack::make(__('Property Name'), 'name', [
                Text::make('name'),
                Text::make('code', function($resource) {
                    return "#{$resource->code}";
                }),
            ]),  
            
            $this->resourceUrls(),
 
            Stack::make(__('Residence Detail'), 'property_type_id', [
                BelongsTo::make(__('Residence Type'), 'propertyType', PropertyType::class),
                BelongsTo::make(__('Accommodation'), 'roomType', RoomType::class),
            ])->sortable(),

            Badge::make(__('Residence Publish Status'), 'marked_as')
                ->labels(static::statuses())
                ->map([ 
                    'draft' => 'danger',
                    'published' => 'success',
                    'pending' => 'warning',
                ])
                ->sortable(),

            BelongsTo::make(__('Residence Host'), 'auth', Host::class),
        ];
    } 

    /**
     * Genereate new code for property
     * 
     * @return string
     */
    public static function generateNewCode()
    {
        while (static::newModel()->withCode($code = time())->first()) {
            $code = time();
        }

        return $code;
    } 

    /**
     * Genereate new code for property
     * 
     * @return string
     */
    public static function statuses()
    {
        return (array) forward_static_call([static::$model, 'statuses']);
    } 

    /**
     * Get the cards available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            Metrics\PropertyPerType::make(),

            Metrics\PropertyPerStatus::make(),

            Metrics\PropertyPerBooking::make(),
        ];
    }

    /**
     * Get the filters available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            Filters\Status::make(),

            Filters\ResidenceType::make(),

            Filters\Accommodation::make(),

            Filters\Booking::make(),

            Filters\Host::make(),
        ];
    }
}
