<?php

namespace Domain\Service\Articles;

use Domain\Grep\ArticleApi;
use Domain\Pagination\Pagination;
use Domain\Pagination\PaginationConfig;
use Domain\Users\Repository\GrepGoalRelationRepositoryInterface;
use Domain\Users\User;

class ArticleService
{
    // private GrepGoalRelationRepositoryInterface $revisionRepository;

    // public function __construct(
    //     GrepGoalRelationRepositoryInterface $revisionRepository
    // ) {
    //     $this->revisionRepository = $revisionRepository;
    // }

    public function getAllArticles(
        PaginationConfig $paginationConfig,
        $distributedAssignmentFilters
    ): Pagination {

        $pagination = new Pagination(
            $paginationConfig,
            $distributedAssignmentFilters,
            resolve(GrepGoalRelationRepositoryInterface::class)
        );

        return $pagination->paginate(null);
    }

    public function getChilds(ArticleApi $articleApi)
    {
        return $articleApi->getChildArticles();
    }
}
