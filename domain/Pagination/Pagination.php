<?php

namespace Domain\Pagination;

use Domain\Users\User;
use Domain\Filters\BaseFilter;

class Pagination extends BasePagination
{
    protected PaginateInterface $resourceRepository;
    private ?BaseFilter $assignmentFilters;

    public function __construct(
        PaginationConfig $paginationConfig,
        ?BaseFilter $assignmentFilters,
        PaginateInterface $resourceRepository
    )
    {
        parent::__construct($paginationConfig);

        $this->resourceRepository = $resourceRepository;
        $this->assignmentFilters = $assignmentFilters;
    }

    public function paginate(?User $user): Pagination
    {
        $pagination = $this->resourceRepository->getItemsForPage($user, $this->paginationConfig, $this->assignmentFilters);
        $this->setItems($pagination->getItems());
        $this->totalItems = $pagination->getTotal();

        return $this;
    }
}
