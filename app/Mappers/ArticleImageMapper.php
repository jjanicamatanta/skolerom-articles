<?php

namespace App\Mappers;

use Domain\Grep\ArticleImage;
use Illuminate\Support\Collection;

class ArticleImageMapper
{

    public function mapToCollectionOfEntitiesFromEloquent(Collection $articleImageModels): Collection
    {
        return $articleImageModels->map(function ($articleImageModel) {
            return $this->mapToEntityFromEloquent($articleImageModel);
        });
    }

    public function mapToEntityFromEloquent($articleImageModel): ArticleImage
    {

        $articleImage = new ArticleImage();
        $articleImage->setImgUrl($articleImageModel->meta_value);
        $articleImage->setImgId($articleImageModel->post_id);


        return $articleImage;
    }
}
