<?php

namespace Armincms\Koomeh\Cypress\Widgets; 

class SinglePost extends Single
{         
    /**
     * Get the template name.
     * 
     * @return string
     */
    public static function templateName(): string
    {
        return \Armincms\Koomeh\Gutenberg\Templates\SinglePost::class;
    }
}
