<?php

namespace Domain\Pagination;

use Illuminate\Support\Collection;

class PaginationCollection
{
    private $items;
    private int $total;

    public function setItems($items): PaginationCollection
    {
        $this->items = $items;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setTotal(int $total): PaginationCollection
    {
        $this->total = $total;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
