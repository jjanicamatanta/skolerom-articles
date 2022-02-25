<?php

namespace Domain\Pagination;

use Domain\Users\User;
use Domain\Filters\BaseFilter;

interface PaginateInterface
{
    public function getItemsForPage(User $user, PaginationConfig $paginationConfig, BaseFilter $filters): PaginationCollection;
}
