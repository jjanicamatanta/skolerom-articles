<?php

namespace App\Mappers;

use Domain\Grep\ArticleImage;
use Domain\Grep\GrepElement;
use Illuminate\Support\Collection;

class GrepElementMapper
{

    public function mapToCollectionOfEntitiesFromEloquent(Collection $models): Collection
    {
        return $models->map(function ($model) {
            return $this->mapToEntityFromEloquent($model);
        });
    }

    public function mapToEntityFromEloquent($model): GrepElement
    {
        $grepElement = new GrepElement();
        $grepElement->setKode($model->kode);
        $grepElement->setDescription($model->description);

        return $grepElement;
    }
}
