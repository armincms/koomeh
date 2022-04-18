<?php

namespace Armincms\Koomeh\Models;
  
use Armincms\Contract\Concerns\HasHits;
use Armincms\Contract\Concerns\InteractsWithFragments;
use Armincms\Contract\Concerns\InteractsWithMedia;
use Armincms\Contract\Concerns\InteractsWithWidgets;
use Armincms\Contract\Contracts\Authenticatable;
use Armincms\Contract\Contracts\HasMedia;
use Armincms\Contract\Contracts\Hitsable;
use Armincms\Markable\Suspendable;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;  
use Zareismail\NovaPolicy\Contracts\Ownable;

class KoomehProperty extends Model implements Authenticatable, HasMedia, Ownable, Hitsable
{   
    use HasHits;
    use InteractsWithFragments;
    use InteractsWithMedia;
    use InteractsWithWidgets;
    use InteractsWithTargomaan;  
    use SoftDeletes;  
    use Suspendable;  

    /**
     * The translation model.
     *
     * @var string
     */
    public const TRANSLATION_MODEL = Translation::class;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['pricings.vacations.vacationDays'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'images', 'price'
    ]; 

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function($model) {
            $model->code ?? $model->fillPropertyCode();
        });

        static::deleting(function($model) {
            if ($model->isForceDeleting()) { 
                $model->translations()->delete();
                $model->amenities()->sync([]);
                $model->conditions()->sync([]);
                $model->pricings()->sync([]);
            }
        });
    }

    /**
     * Get calculated gallery images.
     * 
     * @return integer
     */
    public function getImagesAttribute()
    {
        return $this->getMediasWithConversions()->get('gallery');
    }

    /**
     * Get calculated price.
     * 
     * @return float
     */
    public function getPriceAttribute()
    {
        return floatval(data_get($this->pricing, 'pivot.amount', 0.00));
    }

    /**
     * Get applied pricing.
     * 
     * @return float
     */
    public function getPricingAttribute()
    {
        return once(function() {
            return $this->pricings->findForPresent();
        });
    }

    /**
     * Get availalbe amenities.
     * 
     * @return integer
     */
    public function getAvailableDetailsAttribute($request)
    {
        return $this->groupedAmenities($request)->get('boolean');
    }

    /**
     * Get countable amenities.
     * 
     * @return integer
     */
    public function getCountableDetailsAttribute($request)
    {
        return $this->groupedAmenities($request)->get('number');
    }

    /**
     * Get descriptive amenities.
     * 
     * @return integer
     */
    public function getDescriptiveDetailsAttribute($request)
    {
        return $this->groupedAmenities($request)->get('text');
    }

    /**
     * Get grouped amenities.
     * 
     * @return integer
     */
    public function groupedAmenities($request)
    {
        return $this->amenities->groupBy('field')->map(function($amenities) use ($request) {
            return $amenities->map->serializeForWidget($request);
        });
    }

    /**
     * Get available property statuses.
     * 
     * @return array
     */
    public static function statuses()
    {
        return [
            'draft'     => __('Saved as draft'),
            'pending'   => __('Awaiting approval'),
            'published'   => __('Published on website'),
        ];
    }

    /**
     * Indicate Model Authenticatable.
     * 
     * @return mixed
     */
    public function owner()
    {
        return $this->auth();
    }

    /**
     * Query related User.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auth()
    {
        return $this->belongsTo(\Armincms\Contract\Models\User::class);
    }

    /**
     * Query related KoomehPropertyLocality.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function propertyLocality()
    {
        return $this->belongsTo(KoomehPropertyLocality::class);
    }

    /**
     * Query related KoomehPropertyType.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function propertyType()
    {
        return $this->belongsTo(KoomehPropertyType::class);
    }

    /**
     * Query related KoomehRoomType.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roomType()
    {
        return $this->belongsTo(KoomehRoomType::class);
    }

    /**
     * Query related KoomehReservation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(KoomehReservation::class);
    }

    /**
     * Query related KoomehPaymentBasis.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentBasis()
    {
        return $this->belongsTo(KoomehPaymentBasis::class);
    }

    /**
     * Query related KoomehCondition.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conditions()
    {
        return $this->belongsToMany(KoomehCondition::class, 'koomeh_condition_property');
    }

    /**
     * Query related KoomehAmenity.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function amenities()
    {
        return $this->belongsToMany(KoomehAmenity::class, 'koomeh_amenity_property')
            ->withPivot('value');
    }

    /**
     * Query related LocationState.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(\Armincms\Location\Models\LocationState::class);
    }

    /**
     * Query related LocationCity.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(\Armincms\Location\Models\LocationCity::class);
    }

    /**
     * Query related LocationZone.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo(\Armincms\Location\Models\LocationZone::class);
    }

    /**
     * Query related KoomehPricing.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pricings()
    {
        return $this->belongsToMany(KoomehPricing::class, 'koomeh_pricing_property')
            ->withPivot('amount');
    }

    /**
     * Query related KoomehPricing.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function promotions()
    {
        return $this->belongsToMany(KoomehPromotion::class, 'koomeh_promotion_property');
    }

    /**
     * Get the corresponding cypress fragment.
     * 
     * @return 
     */
    public function cypressFragment(): string
    {
        return \Armincms\Koomeh\Cypress\Fragments\Property::class;
    }

    /**
     * Get the available media collections.
     * 
     * @return array
     */
    public function getMediaCollections(): array
    {
        return [
            'gallery' => [
                'conversions' => ['property-gallery'],
                'multiple'  => true,
                'disk'      => 'image',
                'limit'     => config('koomeh.medai.gallery_length', 10), // count of images
                'accepts'   => ['image/jpeg', 'image/jpg', 'image/png'],
            ], 
        ];
    } 

    /**
     * Serialize the model to pass into the client view for single item.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForDetailWidget($request)
    {
        return array_merge($this->toArray(), $this->serializeForIndexWidget($request), [  
            'host' => $this->auth->serializeForWidget($request), 
            'availableDetails'  => $this->getAvailableDetailsAttribute($request), 
            'countableDetails'  => $this->getCountableDetailsAttribute($request), 
            'descriptiveDetails'=> $this->getDescriptiveDetailsAttribute($request), 
            'groupedDetails' => $this->amenities->groupBy('group_id')
                ->map(function($grouped) use ($request) {
                    return $grouped->map->serializeForWidget($request);
                }), 
        ]);
    }

    /**
     * Serialize the model to pass into the client view for collection of items.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForIndexWidget($request)
    {
        return array_merge($this->toArray(), [
            'creation_date' => $this->created_at->format('Y F d'),
            'last_update'   => $this->updated_at->format('Y F d'), 
            'url'   => $this->getUrl($request), 
            'stateName' => optional($this->state)->name,
            'cityName' => optional($this->city)->name,
            'zoneName' => optional($this->zone)->name,
            'propertyLocality' => $this->propertyLocality,
            'propertyType' => $this->propertyType,
            'roomType' => $this->roomType,
            'pricing' => optional($this->pricing)->name,
            'details' => $this->amenities
                            ->keyBy->getKey()
                            ->map->serializeForWidget($request)
                            ->toArray(),
        ]);
    }

    /**
     * Get the targomaan driver.
     * 
     * @return string
     */
    public function translator() : string
    {
        return 'layeric';
    }

    /**
     * Get the uri value.
     * 
     * @return string
     */
    public function getUri()
    { 
        return $this->getTranslation('uri');
    }

    /**
     * Find a model by its uri.
     *
     * @param  string  $uri
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|static 
     */
    public function findByUri($uri, $columns = ['*'])  
    {
        return $this->withUri($uri)->first($columns);
    }

    /**
     * Query where has the given uri string.
     *
     * @param  string  $uri 
     * @param \Illuminate\Database\Eloquent\Builder $query 
     * @return \Illuminate\Database\Eloquent\Builder 
     */
    public function scopeWithUri($query, $uri)
    {
        return $query->whereHas('translations', function($query) use ($uri) {
            return $query->withUri($uri);
        });
    }

    /**
     * Query where code is equal to the given string.
     *
     * @param  string  $code 
     * @param \Illuminate\Database\Eloquent\Builder $query 
     * @return \Illuminate\Database\Eloquent\Builder 
     */
    public function scopeWithCode($query, $code)
    {
        return $query->whereCode($code);
    }

    /**
     * Fill the `code` attribute of model.
     * 
     * @return static
     */
    public function fillPropertyCode()
    { 
        return $this->forceFill([
            'code' => static::generateNewCode(),
        ]);
    } 

    /**
     * Genereate new code for property
     * 
     * @return string
     */
    public static function generateNewCode()
    { 
        while (static::withCode($code = time())->first()) {
            $code = time();
        }

        return $code;
    } 

    /**
     * Query where authenticated.
     *
     * @var \Illuminate\Database\Eloquent\Model $user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scopeAuthorize($query, $user = null)
    {
        $user = is_null($user) ? request()->user() : $user;

        return $query->where([
            $query->qualifyColumn('auth_id') => is_numeric($user) ? $user : optional($user)->getKey()
        ]);
    }
}
