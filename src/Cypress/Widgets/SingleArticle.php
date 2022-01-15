<?php

namespace Armincms\Koomeh\Cypress\Widgets; 

class SingleArticle extends Single
{         
    /**
     * Get the template name.
     * 
     * @return string
     */
    public static function templateName(): string
    {
        return \Armincms\Koomeh\Gutenberg\Templates\SingleArticle::class;
    }
}
