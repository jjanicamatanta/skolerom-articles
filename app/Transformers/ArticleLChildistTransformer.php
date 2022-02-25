<?php

namespace App\Transformers;

use Domain\Grep\ArticleApi;
use Domain\Grep\ArticleImage;
use Domain\Grep\GrepGoalRelation;

use League\Fractal\TransformerAbstract;

class ArticleLChildistTransformer extends TransformerAbstract
{

    public function transform(ArticleApi $articleApi): array
    {
        return [
            
            'title' => $articleApi->getPostTitle(),
            'id' => strval($articleApi->getId()),
            // 'level' => json_decode(json_encode(
            //     [
            //         "img_url" => is_null($images->getImgUrl()) ? false : $images->getImgUrl() , 
            //         "img_id" => is_null($images->getImgId()) ? 0 : $images->getImgId()
            //     ]), true)
        ];
    }
 
}
