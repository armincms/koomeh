<?php

namespace Armincms\Koomeh\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class PricingCollection extends Collection 
{
	/**
	 * Find an item for current date.
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function findForPresent()
	{ 
		return $this->first(function($pricing) {
			return $pricing->vacations->present()->isNotEmpty();
		}, function() { 
			return $this->findForToday();
		});
	}

	/**
	 * Find an item for current day.
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function findForToday()
	{  
		return $this->filter->isAvailableForToday()->first();
	}
}