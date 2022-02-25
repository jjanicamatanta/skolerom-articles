<?php

namespace Domain\Pagination;

class PaginationConfig
{
    protected ?int $page;
    protected ?int $perPage;

    protected const DEFAULT_ITEMS_AMOUNT_PER_PAGE = 8;

    public function __construct()
    {
        $this->page = 0;
        $this->perPage = self::DEFAULT_ITEMS_AMOUNT_PER_PAGE;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): PaginationConfig
    {
        if (is_null($page)) {
            return $this;
        }

        $this->page = $page;

        return $this;
    }

    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    public function setPerPage(?int $perPage): PaginationConfig
    {
        if (is_null($perPage)) {
            return $this;
        }

        $this->perPage = $perPage;

        return $this;
    }

    public function calculateOffset(): int
    {
        
        return ($this->page - 1) * $this->perPage;
    }
}
