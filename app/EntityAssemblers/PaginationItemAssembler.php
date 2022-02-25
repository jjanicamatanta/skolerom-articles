<?php

namespace App\EntityAssemblers;

use Domain\Pagination\PaginationCollection;
use Illuminate\Support\Collection;

class PaginationItemAssembler
{
    public static function createEntityFromCollection($paginationData): PaginationCollection
    {
        $assignmentFilters = new PaginationCollection();
        $assignmentFilters->setItems($paginationData->get('items'));
        $assignmentFilters->setTotal($paginationData->get('total'));

        return $assignmentFilters;
    }
}
