<?php

namespace App\Mappers;

use Domain\Grep\StudentLevel;
use Illuminate\Support\Collection;

class StudentLevelMapper
{

    public function mapToCollectionOfEntitiesFromEloquent(Collection $studentLevelModels): Collection
    {
        return $studentLevelModels->map(function ($studentLevelModel) {
            // dd($studentLevelModel);
            return $this->mapToEntityFromEloquent($studentLevelModel);
        });
    }

    public function mapToEntityFromEloquent($studentLevelModel): StudentLevel
    {
        $studentLevel = new StudentLevel();
        $studentLevel->setTermId($studentLevelModel->term_id);
        $studentLevel->setName($studentLevelModel->name);
        $studentLevel->setSlug($studentLevelModel->slug);
        
        return $studentLevel;
    }
}
