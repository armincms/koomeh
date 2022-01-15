<?php

namespace Armincms\Koomeh\Policies;

use Armincms\Contract\Policies\Policy;
use Armincms\Contract\Policies\SoftDeletes;

class Property extends Policy
{   
	use SoftDeletes;

    /**
     * Determine whether the user can restore the admin.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $admin
     * @return mixed
     */
    public function publish($user, $model)
    { 
		return true;
	}
}
