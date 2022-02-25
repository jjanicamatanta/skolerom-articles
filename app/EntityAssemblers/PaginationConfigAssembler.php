<?php

namespace App\EntityAssemblers;

use Domain\Pagination\PaginationConfig;
use Illuminate\Support\Collection;

class PaginationConfigAssembler
{
    public static function createEntityFromHttp(Collection $paginationData): PaginationConfig
    {
        $paginationConfig = new PaginationConfig();
        $paginationConfig->setPage($paginationData->get('page'));
        $paginationConfig->setPerPage($paginationData->get('per_page'));

        return $paginationConfig;
    }
}
