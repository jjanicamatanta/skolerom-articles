<?php

namespace Domain\Article\Repository;

interface StudentLevelRepositoryInterface
{
    public function getStudentLevels(int $articleId);

    public function getTaxonomies(int $objectId);

}
