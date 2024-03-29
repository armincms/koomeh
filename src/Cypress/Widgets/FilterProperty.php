<?php

namespace Armincms\Koomeh\Cypress\Widgets;

use Armincms\Contract\Gutenberg\Templates\Pagination;
use Armincms\Contract\Gutenberg\Widgets\BootstrapsTemplate;
use Armincms\Contract\Gutenberg\Widgets\ResolvesDisplay;
use Armincms\Koomeh\Nova\Amenity;
use Armincms\Koomeh\Nova\Property;
use Armincms\Koomeh\Nova\PropertyLocality;
use Armincms\Koomeh\Nova\PropertyType;
use Armincms\Koomeh\Nova\Reservation;
use Armincms\Koomeh\Nova\RoomType;
use Armincms\Koomeh\Gutenberg\Templates\IndexProperty;
use Armincms\Location\Nova\City;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use OptimistDigital\MultiselectField\Multiselect;
use Whitecube\NovaFlexibleContent\Flexible;
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Gutenberg\Cacheable;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Gutenberg\GutenbergWidget;

class FilterProperty extends GutenbergWidget implements Cacheable
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

        $this->when($this->metaValue('pagination'), function() use ($request, $layout) {
            $template = $this->bootstrapTemplate($request, $layout, $this->metaValue('pagination'));

            $this->displayResourceUsing(function($attributes) use ($template) {
                return $template->gutenbergTemplate($attributes)->render();
            }, 'pagination');
        }, function() {
            $this->displayResourceUsing(function($attributes) { }, 'pagination');
        });
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
                ->nullable()
                ->withMeta([
                    'placeholder' => __('Display only one page')
                ]),

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

            Select::make(__('Filter by locality'), 'config->propertyLocality')
                ->options(PropertyLocality::newModel()->get()->keyBy->getKey()->map->name)
                ->nullable(),

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
                ->options(Amenity::newModel()->with('group')->get()->keyBy->getKey()->map(function($amenity) {
                    return [
                        'label' => $amenity->name,
                        'group' => optional($amenity->group)->name,
                    ];
                }))->saveAsJSON(),

            Number::make(__('Display per page'), 'config->per_page')
                ->default(1)
                ->min(1)
                ->required()
                ->rules('required', 'min:1')
                ->help(__('Number of items that should be display on each page.')),

            Boolean::make(__('Searchable'), 'config->searchable')
                ->help(__('Determine if should filter when search string appears in the request.')),
        ];
    }

    /**
     * Serialize the widget fro template.
     *
     * @return array
     */
    public function serializeForDisplay(): array
    {
        $queryCallback = function($query) {
            $query->unless($this->metaValue('pagination'), function($query) {
                $query->limit($this->metaValue('per_page'));
            });

            $query->where(function($query) {
                if (! $this->metaValue('searchable') || ! request()->query('search')) {
                    return;
                }

                $query->whereHas('translations', function($query) {
                    $query->where('name', 'like', $this->getSearchString());
                });
                $query->orWhereHas('state', function($query) {
                    $query->where('name->'. app()->getLocale(), 'like', $this->getSearchString());
                });
                $query->orWhereHas('city', function($query) {
                    $query->where('name->'. app()->getLocale(), 'like', $this->getSearchString());
                });
                $query->orWhereHas('zone', function($query) {
                    $query->where('name->'. app()->getLocale(), 'like', $this->getSearchString());
                });
            });

            $query->where(function($query) {
                $query->when($this->metaValue('propertyLocality'), function($query) {
                    $query->where('property_locality_id', $this->metaValue('propertyLocality'));
                });
                $query->when($this->metaValue('propertyType'), function($query) {
                    $query->where('property_type_id', $this->metaValue('propertyType'));
                });

                $query->when($this->metaValue('roomType'), function($query) {
                    $query->where('room_type_id', $this->metaValue('roomType'));
                });

                $query->when($this->metaValue('reservation'), function($query) {
                    $query->where('reservation_id', $this->metaValue('reservation'));
                });

                $query->when($this->metaValue('city'), function($query) {
                    $query->where('city_id', (array) $this->metaValue('city'));
                });
            });

            $query->with([
                'propertyLocality',
                'propertyType',
                'roomType',
                'media',
                'state',
                'city',
                'zone',
                'translations',
                'promotions' => function($query) {
                    $query->where('expires', '>=', now());
                },
                'amenities' => function($query) {
                    $query->whereKey((array) $this->metaValue('details'))->with('group');
                }
            ]);
        };

        $orderings = function($query) {
            $query->when($this->metaValue('direction') === 'asc', function($query) {
                return $query->latest($this->metaValue('ordering'));
            }, function($query) {
                return $query->oldest($this->metaValue('ordering'));
            });
        };

        $properties = Property::newModel()
            ->whereHas('promotions', function($query) {
                $query->where('expires', '>=', now());
                $query->when($this->metaValue('propertyLocality'), function($query) {
                    $query->where('config->orders->locality', true);
                });
                $query->when($this->metaValue('propertyType'), function($query) {
                    $query->orWhere('config->orders->type', true);
                });

                $query->when($this->metaValue('roomType'), function($query) {
                    $query->orWhere('config->orders->room', true);
                });

                $query->when($this->metaValue('city'), function($query) {
                    $query->orWhere('config->orders->city', true);
                });

            })
            ->published()
            ->tap($queryCallback)
            ->where($orderings)
            ->union(
                Property::newModel()->whereDoesntHave('promotions')
                    ->published()
                    ->where($orderings)
                    ->tap($queryCallback)
            )
            ->when($this->metaValue('pagination'), function($query) {
                return $query->paginate($this->metaValue('per_page'));
            }, function($query) {
                return $query->simplePaginate($this->metaValue('per_page'), ['*'], $this->name);
            });

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

    public function getSearchString()
    {
        $searchString = trim(trim(request()->query('search'), '%'));

        return "%{$searchString}%";
    }

    /**
     * Get cache key.
     *
     * @return string
     */
    public function cacheKey()
    {
        return parent::cacheKey() . md5(request()->getQueryString());
    }
}
