<?php

namespace Domain\Grep;

use Domain\Article\Repository\StudentLevelRepositoryInterface;
use Illuminate\Support\Collection;

class StudentLevel
{
    protected ?int $termId;
    protected ?string $name;
    protected ?string $slug;

    protected ?Collection $taxonomies;

    private StudentLevelRepositoryInterface $studentLevelRepository;

    public function __construct()
    {
        $this->termId = null;
        $this->name = null;
        $this->slug = null;

        $this->studentLevelRepository = resolve(StudentLevelRepositoryInterface::class);
    }

    public function loadTaxonomies()
    {
        $this->taxonomies = $this->studentLevelRepository->getTaxonomies($this->id);
    }

    public function getTermId(): ?int
    {
        return $this->termId;
    }

    public function setTermId(?int $termId): StudentLevel
    {
        $this->termId = $termId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): StudentLevel
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): StudentLevel
    {
        $this->slug = $slug;
        return $this;
    }
}
