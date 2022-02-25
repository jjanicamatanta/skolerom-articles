<?php

namespace Domain\Grep;


class StudentLevelChildArticle
{
    protected ?int $id;
    protected ?string $title;
    protected ?string $slug;

    public function __construct()
    {
        $this->termId = null;
        $this->name = null;
        $this->slug = null;
        
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
