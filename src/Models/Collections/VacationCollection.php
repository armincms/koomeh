<?php

namespace Armincms\Koomeh\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class VacationCollection extends Collection 
{
	/**
	 * Find an item for current date.
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function present()
	{ 
		return $this->filter(function($vacation) {
			return $vacation->vacationDays->present()->isNotEmpty();
		});
	}
}