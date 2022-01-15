<?php

namespace Armincms\Koomeh\Cypress\Fragments; 

class Podcast extends Blog 
{      
    /**
     * Get the resource Model class.
     * 
     * @return
     */
    public function model(): string
    {
        return \Armincms\Koomeh\Models\Podcast::class;
    }  
}
