<?php

namespace Armincms\Koomeh\Cypress\Fragments; 

class Post extends Blog 
{      
    /**
     * Get the resource Model class.
     * 
     * @return
     */
    public function model(): string
    {
        return \Armincms\Koomeh\Models\Post::class;
    }  
}
