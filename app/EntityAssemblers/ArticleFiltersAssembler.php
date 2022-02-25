<?php

namespace App\EntityAssemblers;

use Domain\Filters\ArticleFilters;

use Illuminate\Support\Collection;

class ArticleFiltersAssembler
{
    public static function createEntityFromHttp(Collection $paginationData): ArticleFilters
    {
        $articleFilters = new ArticleFilters();
      
        $articleFilters->setSubjectIds($paginationData->get('student_subject_id'));
        $articleFilters->setGradeId($paginationData->get('student_grade_id'));
        $articleFilters->setGoalIds($paginationData->get('goal_id'));
        $articleFilters->setCoreElementIds($paginationData->get('core_id'));
        $articleFilters->setMainTopicIds($paginationData->get('student_disciplin_id'));
        
        $articleFilters->setSource($paginationData->get('student_source_id'));
        $articleFilters->setLang($paginationData->get('lang'));
        $articleFilters->setSearchTitle($paginationData->get('search_title'));
        $articleFilters->setIds($paginationData->get('ids'));

        $articleFilters->setOrder($paginationData->get('order_by'));

        return $articleFilters;
    }
}
