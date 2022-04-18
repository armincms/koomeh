<?php

namespace Armincms\Koomeh\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class PromotionCollection extends Collection 
{
	/**
	 * Filter tagged promotions.
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function tagged()
	{ 
		return $this->filter(function($promotion) {
			return $promotion->isTagged();
		});
	}
}