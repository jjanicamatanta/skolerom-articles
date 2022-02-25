<?php

namespace Domain\Filters;

abstract class BaseFilter
{
    protected ?string $searchQuery;

    abstract public function getSearchQuery(): ?string;
    abstract public function setSearchQuery(?string $searchQuery): self;

}
