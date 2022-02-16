<?php

namespace Armincms\Koomeh\Models;
  
use Armincms\Contract\Concerns\InteractsWithFragments;
use Armincms\Contract\Concerns\InteractsWithMedia;
use Armincms\Contract\Concerns\InteractsWithWidgets;
use Armincms\Contract\Contracts\Authenticatable;
use Armincms\Contract\Contracts\HasMedia;
use Armincms\Markable\Suspendable;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;  

class KoomehProperty extends Model implements Authenticatable, HasMedia
{   
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'images'
    ]; 

    /**
     * Get calculated gallery images.
     * 
     * @return integer
     */
    public function getImagesAttribute()
    {
        return $this->getMedia('gallery')->map(function($media) { 
            return collect($media->getGeneratedConversions())->map(function($value, $conversion) { 
                return $this->getFirstMediaUrl('gallery', $conversion);
            });
        });
    }

    /**
     * Get availalbe amenities.
     * 
     * @return integer
     */
    public function getAvailableDetailsAttribute()
    {
        return $this->groupedAmenities()->get('boolean');
    }

    /**
     * Get countable amenities.
     * 
     * @return integer
     */
    public function getCountableDetailsAttribute()
    {
        return $this->groupedAmenities()->get('number');
    }

    /**
     * Get descriptive amenities.
     * 
     * @return integer
     */
    public function getDescriptiveDetailsAttribute()
    {
        return $this->groupedAmenities()->get('text');
    }

    /**
     * Get grouped amenities.
     * 
     * @return integer
     */
    public function groupedAmenities()
    {
        return $this->amenities->groupBy('field')->map(function($amenities) {
            return $amenities->map(function($amenity) {
                return [
                    'name' => $amenity->name,
                    'help' => $amenity->help,
                    'group'=> data_get($amenity, 'group.name'),
                    'icon' => $amenity->icon,
                    'value'=> data_get($amenity, 'pivot.value'),
                ];
            });
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
     * Query related User.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auth()
    {
        return $this->belongsTo(\Armincms\Contract\Models\User::class);
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
        return array_merge($this->toArray(), [
            'creation_date' => $this->created_at->format('Y F d'),
            'last_update'   => $this->updated_at->format('Y F d'),
            'author'=> $this->auth->fullname(), 
            'url'   => $this->getUrl($request),
            'availableDetails'  => $this->groupedAmenities()->get('boolean'), 
            'countableDetails'  => $this->groupedAmenities()->get('number'), 
            'descriptiveDetails'=> $this->groupedAmenities()->get('text'),
            'details' => $this->amenities->keyBy->getKey->map->toArray(),
            'stateName' => optional($this->state)->name,
            'cityName' => optional($this->city)->name,
            'zoneName' => optional($this->zone)->name,
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
            'details' => $this->amenities->keyBy->getKey()->map(function($amenity) {
                return [
                    'name' => $amenity->name,
                    'help' => $amenity->help,
                    'group'=> data_get($amenity, 'group.name'),
                    'icon' => $amenity->icon,
                    'value'=> data_get($amenity, 'pivot.value'),
                ];
            })->toArray(),
            'stateName' => optional($this->state)->name,
            'cityName' => optional($this->city)->name,
            'zoneName' => optional($this->zone)->name,
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
