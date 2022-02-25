<?php

namespace Domain\Pagination;

use App\Exceptions\NotFoundApiException;
use Domain\Users\User;
use Illuminate\Support\Collection;

abstract class BasePagination
{
    protected int $totalItems;
    private int $count;

    private int $perPage;
    private int $currentPage;

    protected int $totalPages;
    protected $items;

    private const DEFAULT_LIMIT = 8;

    public function __construct(PaginationConfig $paginationConfig)
    {
        $confPage = $paginationConfig->getPage();
        $confPerPage = $paginationConfig->getPerPage();

        $this->totalItems = 0;
        $this->count = 0;

        //TODO This is may not be obvious. And this is the place for bugs
        $this->paginationConfig = $paginationConfig;
        $this->perPage = $confPerPage ?? self::DEFAULT_LIMIT;
        $this->currentPage = $confPage ?? 0;

        $this->totalPages = 0;

    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): self
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getPaginationConfig(): PaginationConfig
    {
        return $this->paginationConfig;
    }

    public function setPaginationConfig(PaginationConfig $paginationConfig): self
    {
        $this->paginationConfig = $paginationConfig;

        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): self
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items): self
    {
        $isEmpty = $items->isEmpty();
        $notInArray = !in_array($this->currentPage, [0, 1]);

        if ($isEmpty && $notInArray) {
            throw new NotFoundApiException();
        }
        $this->items = $items;

        return $this;
    }

    public function calculateOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public abstract function paginate(User $user): Pagination;
}
