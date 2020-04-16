<?php

namespace Armincms\Koomeh\Nova;

use Armincms\Tab\Tab;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Armincms\Nova\Fields\Images;
use Armincms\Fields\BelongsToMany;
use Armincms\Fields\MorphedByMany;
use Armincms\Facility\Facility as FacilityModel;
use Armincms\Facility\Nova\Fields\ManyToMany;
use OwenMelbz\RadioField\RadioButton;
use Armincms\RawData\Common;

class Residence extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Armincms\\Koomeh\\Residence';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'text';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
    ];
    public static $with = [
    ];

    /**
     * The columns that should be searched in the translation table.
     *
     * @var array
     */
    public static $searchTranslations = [
        'text'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return static::singularLabel();
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return
            Tab::make('primary', function ($tab) {
                $tab->group('specifications', [$this, 'specificationsFields'])
                    ->label(__("Residences Specifications"));

                $tab->group('media', [$this, 'mediaFields'])
                    ->label(__("Media"));

                $tab->group('location', [$this, 'locationFields'])
                    ->label(__("Location"));

                $tab->group('condition', [$this, 'conditionFields'])
                    ->label(__("Conditions"));

                $tab->group('setting', [$this, 'settingFields'])
                    ->label(__("Settings"));
            })->toArray();
    }

    public function specificationsFields()
    {
        return  [
            ID::make()->sortable(),

            BelongsTo::make(
                __("Residences Type"),
                'residencesType',
                ResidencesType::class
            )->withoutTrashed(),

            BelongsTo::make(
                    __("Reservation Method"), 'reservation', ResidencesReservation::class
                )
                ->withoutTrashed()
                ->hideFromIndex(Configuration::option("_residences_reservation_")) 
                ->hideFromDetail(Configuration::option("_residences_reservation_")) 
                ->hideWhenCreating(Configuration::option("_residences_reservation_")) 
                ->hideWhenUpdating(Configuration::option("_residences_reservation_")),

            BelongsToMany::make(__("Pricings"), 'pricings', ResidencesPricing::class)
                ->fields(function ($request) {
                    $pricing = ResidencesPricing::newModel()->find($request->relatedId);

                    return $pricing->adaptive ? [] : [

                        RadioButton::make(__("Adaptive"), "adaptive")->options([
                            __("No"), __("Yes")
                        ])->toggle([
                            1 => ['price']
                        ])->required()->default(0),


                        $this->priceField("Price", 'price', option("_residences_currency_", "IRR"))
                            ->required()
                            ->rules(['numeric', function ($attribute, $value, $fail) {
                                if (request('adaptive') == 0 && floatval($value) <= 0) {
                                    $fail(__("You should enter valid price"));
                                }
                            }]),

                    ];
                })
                ->pivots()
                ->fillUsing(function ($value) {
                    return [
                        'adaptive' => (int) ($value['adaptive'] ?? 0),
                        'price' => floatval($value['price'] ?? 0),
                    ];
                }),

            MorphedByMany::make(__("Facilities"), 'facilities', Facility::class)
                ->fields(function ($request) {
                    $fieldClass = data_get(
                        $facility = FacilityModel::find($request->relatedId),
                        'field'
                    );

                    if (! class_exists($fieldClass)) {
                        $fieldClass = Text::class;
                    }

                    return [tap($fieldClass::make($facility->label, 'value'), function ($field) use ($facility) {
                        if (method_exists($field, 'options')) {
                            $field
                                ->options(collect($facility->options)->pluck('text'))
                                ->required()
                                ->rules('required');
                        }

                        if (method_exists($field, 'displayUsingLabels')) {
                            $field->displayUsingLabels();
                        }
                    })];
                })
                ->pivots()
                ->hideFromIndex()
                ->fillUsing(function ($value) {
                    if (isset($value['value']) && is_array($value['value'])) {
                        $value['value'] = json_encode($value['value']);
                    }

                    return $value;
                }),

            BelongsToMany::make(__("Usage"), 'usages', ResidencesUsage::class)
                ->required()
                ->rules('required')
                ->display("usage"),

            BelongsToMany::make(__("Residences Condition"), 'conditions', ResidencesCondition::class)
                ->hideFromIndex()
                ->display("condition")
                ->hideFromIndex(),

            $this->translatable([
                $this->abstractField(),
                $this->gutenbergField(),
            ]),
        ];
    }

    public function mediaFields()
    {
        return  [
            $this
                ->imageField() 
                ->stacked()
                ->customPropertiesFields([
                    $this->toggle(__("Master"), "master"),
                ]),
        ];
    }


    public function locationFields()
    {
        return array_merge([
            BelongsTo::make(__("City"), 'city', \Armincms\Location\Nova\City::class)
                ->searchable()
                ->hideFromIndex(),

            BelongsTo::make(__("Zone"), 'zone', \Armincms\Location\Nova\Zone::class)
                ->searchable()
                ->hideFromIndex(),

            Text::make(__("Location"), function() {
                return optional($this->zone)->name ." - ".optional($this->city)->name;
            }),
        ], $this->coordinates(), $this->translatable([
            Text::make(__("Address"), 'address')
                ->nullable()
                ->hideFromIndex(),
        ])->data);
    }

    public function conditionFields()
    {
        return array_merge($this->durationField()->onlyOnForms()->toArray(), [
            Text::make(__("Duration"), function () {
                return $this->duration['period'].PHP_EOL.Common::durations()->get(
                    $this->duration['count']
                );
            }),

            Number::make(__("Guest"), 'guest')
                ->default(1)
                ->rules('min:1')
                ->min(1),

            Number::make(__("Adult"), 'adult')
                ->default(0)
                ->rules('min:0')
                ->min(0)
                ->hideFromIndex(),

            Number::make(__("Child"), 'child')
                ->default(0)
                ->rules('min:0')
                ->min(0)
                ->hideFromIndex(),

            Number::make(__("Babe"), 'babe')
                ->default(0)
                ->rules('min:0')
                ->min(0)
                ->hideFromIndex(),
        ]);
    }

    public function settingFields()
    {
        return [
            $this->userField(__("Agent"), "agent")
                ->nullable()
                ->hideFromIndex(),

            $this->userField(__("Owner"))
                ->nullable()
                ->hideFromIndex(), 

            $this->toggle(__("Online Reservation"), 'reservable'),
        ];
    }
}
