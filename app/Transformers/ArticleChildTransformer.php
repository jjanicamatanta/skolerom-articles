<?php

namespace App\Transformers;

use Domain\Grep\ArticleApi;
use Domain\Grep\ArticleImage;
use Domain\Grep\GrepGoalRelation;

use League\Fractal\TransformerAbstract;

class ArticleChildTransformer extends TransformerAbstract
{
 
    public function transform($articleChild): array
    {
        // return $articleChild;
        dd(1,$articleChild,2);
        return [
            'excerpt' => $articleChild->getPostExpert(),
            'title' => $articleChild->getPostTitle(),
            'id' => strval($articleChild->getId()),
            'link' => '',
            'guid' => $articleChild->getGuid(),
            'post_type' => $articleChild->getPostType(),
        ];
    }
 

  
}
