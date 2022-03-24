<?php

namespace Armincms\Koomeh\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class VacationDayCollection extends Collection 
{
	/**
	 * Find an item for current date.
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function present()
	{ 
		return $this->filter->includesPresent();
	}
}