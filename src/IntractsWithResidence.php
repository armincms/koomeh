<?php
namespace Armincms\Koomeh;  

trait IntractsWithResidence 
{   
    public function residences()
    {
        return $this->hasMany(Residence::class);
    }
}
