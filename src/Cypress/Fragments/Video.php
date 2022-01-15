<?php

namespace Armincms\Koomeh\Cypress\Fragments; 

class Video extends Blog 
{      
    /**
     * Get the resource Model class.
     * 
     * @return
     */
    public function model(): string
    {
        return \Armincms\Koomeh\Models\Video::class;
    }  
}
