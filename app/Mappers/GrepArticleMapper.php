<?php

namespace App\Mappers;

use Domain\Grep\ArticleApi;
use Domain\Grep\ArticleImage;
use Domain\Grep\GrepGoalRelation;
use Illuminate\Support\Collection;

class GrepArticleMapper
{

    public function mapToCollectionOfEntitiesFromEloquent($articleModels)
    {
        
        return $articleModels->map(function ($articleModel) {
            return $this->mapToEntityFromEloquent($articleModel);
        });
    }

    public function mapToEntityFromEloquent($articleModel, $loadTaxs = true): ArticleApi
    {

        // dd($articleModel);
        $articleApi = new ArticleApi();
        $articleApi->setId($articleModel->ID);
        $articleApi->setPostTitle($articleModel->post_title);
        $articleApi->setPostType($articleModel->post_type);
        $articleApi->setGuid($articleModel->guid);
        $articleApi->setPostExpert($articleModel->post_excerpt);
        // $articleApi->setImages($this->getImages($articleModel->images));
        if($loadTaxs)
            $articleApi->loadTaxonomies();

        // $articleApi->setTermId($articleModel->term_id);
        // $articleApi->setName($articleModel->name);

        return $articleApi;
    }

    public function getImages($imagesString): Collection
    {
        
        $imagesJsonString = new Collection(explode('|', $imagesString));

        return $imagesJsonString->map(function ($imagesJsonString) {
             
            $imageJson = json_decode($imagesJsonString);
            
            return $this->getImage($imageJson);
        });
    }

    public function getImage($imageJson)
    {
        $articleImage = new ArticleImage();
        
        $articleImage->setImgId($imageJson->id);
        $articleImage->setImgUrl($imageJson->url);
        return $articleImage;
    }
}
