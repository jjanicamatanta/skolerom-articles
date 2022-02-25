<?php

namespace App\Transformers;

use Domain\Grep\ArticleApi;
use Domain\Grep\ArticleImage;
use Domain\Grep\GrepElement;
use Domain\Grep\GrepGoalRelation;

use League\Fractal\TransformerAbstract;

class GrepElementTransformer extends TransformerAbstract
{


    public function transform(GrepElement $grepElement): array
    {
        return [
            'kode' => $grepElement->getKode(),
            'description' => $grepElement->getDescription(),
        ];
    }
}
