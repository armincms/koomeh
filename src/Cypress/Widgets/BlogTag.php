<?php

namespace Armincms\Koomeh\Cypress\Widgets; 

use Armincms\Taggable\Cypress\Widgets\SingleTag;

class BlogTag extends SingleTag
{       
    /**
     * Get the category related content template name.
     * 
     * @return string
     */
    public static function contentTemplateName(): string
    {
        return \Armincms\Koomeh\Gutenberg\Templates\IndexPost::class;
    } 

    /**
     * Get the related model.
     * 
     * @param  string $relationship 
     * @return string
     */
    protected static function relationModel(string $relationship): string
    { 
        return \Armincms\Koomeh\Models\Post::class;
    }
}
