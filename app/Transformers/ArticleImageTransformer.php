<?php

namespace App\Transformers;

use Domain\Grep\ArticleImage;
use Domain\Grep\StudentLevel;

use League\Fractal\TransformerAbstract;

class ArticleImageTransformer extends TransformerAbstract
{

     

    public function transform(ArticleImage $articleImage): array
    {
        $url = $articleImage->getImgUrl();
        $id = $articleImage->getImgId();
        return [
            'img_url' => is_null($url) ? false : $url,
            'img_id' => is_null($id) ? 0 : $id,
            

        ];
    }
 
}
