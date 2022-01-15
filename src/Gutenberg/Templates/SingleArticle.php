<?php

namespace Armincms\Koomeh\Gutenberg\Templates; 
 
use Zareismail\Gutenberg\Variable;

class SingleArticle extends SinglePost 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return array_merge(parent::variables(), [ 
            Variable::make('source', __('Article Source URL')), 
        ]);
    } 
}