<?php

namespace App\Mappers;

use App\Models\GrepGoalRelationModel;
use Domain\Grep\GrepGoalRelation;
use Illuminate\Support\Collection;

class GrepGoalRelationMapper    
{

    public function mapToCollectionOfEntitiesFromEloquent(Collection $assignmentModels): Collection
    {
        return $assignmentModels->map(function (GrepGoalRelationModel $assignmentModel) {
            return $this->mapToEntityFromEloquent($assignmentModel);
        });
    }

    public function mapToEntityFromEloquent(GrepGoalRelationModel $grepGoalRelationModel): GrepGoalRelation
    {
        $assignment = new GrepGoalRelation();
        $assignment->setGoalRelationId($grepGoalRelationModel->goal_relation_id);
        $assignment->setGoalId($grepGoalRelationModel->goal_id);
        $assignment->setKode($grepGoalRelationModel->kode);
        $assignment->setSubjectId($grepGoalRelationModel->subject_id);
        $assignment->setGradeId($grepGoalRelationModel->grade_id);
        $assignment->setLangId($grepGoalRelationModel->lang_id);

        return $assignment;
    }
}
