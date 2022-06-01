<?php

namespace Armincms\Koomeh\Models; 

use Armincms\Contract\Concerns\Configurable;
use Armincms\Orderable\Contracts\Salable;
use Zareismail\Markable\HasActivation;
use Zareismail\Markable\Markable;

class KoomehPromotion extends Model implements Salable
{     
    use Configurable;
    use HasActivation;
    use Markable;
    
	/**
	 * Query related KoomehPricing.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function properties()
	{
		return $this->belongsToMany(KoomehProperty::class, 'koomeh_promotion_property')->withTimestamps();
	} 

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\PromotionCollection($models);
    }

    /**
     * Determine if the promotion is tagged.
     * 
     * @return boolean
     */
    public function isTagged()
    {
    	return boolval($this->tagged);
    } 

    /**
     * Get the sale price currency.
     * 
     * @return decimal
     */
    public function saleCurrency(): string
    {
        return 'IRR';
    }

    /**
     * Get the sale price of the item.
     * 
     * @return decimal
     */
    public function salePrice(): float
    {
        return $this->oldPrice();
    }

    /**
     * Get the real price of the item.
     * 
     * @return decimal
     */
    public function oldPrice(): float
    {
        return $this->price;
    }

    /**
     * Get the item name.
     * 
     * @return decimal
     */
    public function saleName(): string
    {
        return $this->name;
    }

    /**
     * Get the item description.
     * 
     * @return decimal
     */
    public function saleDescription(): string
    { 
        return $this->help;
    }
}
