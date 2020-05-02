<?php
namespace Armincms\Koomeh;

use Armincms\Concerns\Authorization;
use Armincms\Concerns\IntractsWithMedia;
use Armincms\Contracts\Authorizable;
use Armincms\Facility\Facilities; 
use Armincms\Location\Location;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Residence extends Model implements HasMedia, Authorizable 
{
	use SoftDeletes, IntractsWithMedia, Facilities, Authorization;

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['pricings'];
	protected $casts = [
		'duration' => 'json',
	];

	protected $medias = [
		'gallery' => [
			'multiple' => true,
			'disk' => 'armin.image',
			'schemas' => [
				'residence', 'residence.list', '*',
			],
		],
		'video' => [
			'disk' => 'armin.video',
		],
	];

	public static function boot() {
		parent::boot();

		self::saving(function ($model) {
			$model->code || $model->generateCode();
		});

		static::saved(function ($model) {
			$model->relationLoaded('agent') || $model->load('agent');

			if (!($model->agent instanceof Authenticatable)) {
				$model->agent()->associate(request()->user());
				$model->save();
			}
		});
	}

	public function generateCode() {
		$this->code = time();

		return $this;
	}

	/**
	 * Indicate Authenticatable.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function agent() {
		return $this->morphTo();
	}

	/**
	 * The realtive `ResidencesPricing`'s
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function pricings() {
		return $this->BelongsToMany(ResidencesPricing::class, 'residences_pricing')
			->withPivot('price', 'id', 'adaptive');
	}

	public function residencesType() {
		return $this->belongsTo(ResidencesType::class);
	}

	public function reservation() {
		return $this->belongsTo(
			ResidencesReservation::class,
			'residences_reservation_id'
		);
	}

	public function usages() {
		return $this->belongsToMany(ResidencesUsage::class, 'residences_usage');
	}

	public function conditions() {
		return $this->belongsToMany(
			ResidencesCondition::class,
			'residences_condition'
		)->withPivot('id');
	}

	public function city() {
		return $this->belongsTo(Location::class);
	}

	public function zone() {
		return $this->belongsTo(Location::class);
	}
}
