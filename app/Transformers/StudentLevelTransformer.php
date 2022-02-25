<?php

namespace App\Transformers;

use Domain\Grep\ArticleApi;
use Domain\Grep\StudentLevel;

use League\Fractal\TransformerAbstract;

class StudentLevelTransformer extends TransformerAbstract
{

    // protected $defaultIncludes = [
    //     'childArticles'
    // ];

    private ArticleApi $articleApi;

    public function __construct(ArticleApi $articleApi)
    {
        $this->articleApi = $articleApi;
    }


    public function transform(StudentLevel $studentLevel): array
    {
        return [
            'term_id' => $studentLevel->getTermId(),
            'name' => $studentLevel->getName(),
            'slug' => $studentLevel->getSlug(),
            // 'childArticles' => $this->articleApi->getChildArticles()
            'childArticles' => [json_decode(json_encode(
                [
                    "id" => $this->articleApi->getId(),
                    "title" => $this->articleApi->getPostTitle(),
                    "level" => [
                        "term_id" => $studentLevel->getTermId(),
                        "name" => $studentLevel->getName(),
                        "slug" => $studentLevel->getSlug()
                    ]
                ]
            ), true)]
        ];
    }

    // public function getChildArticles(StudentLevel $studentLevel)
    // {
    //     $article = $studentLevel->getChildArticles();
    //     return $this->collection($childArticles, new StudentLevelChildArticleTransformer());
    // }
}
