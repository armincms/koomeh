<?php

namespace Armincms\Koomeh\Policies;

use Armincms\Contract\Policies\Policy;
use Armincms\Contract\Policies\SoftDeletes;

class Vacation extends Policy
{   
	use SoftDeletes;
}
