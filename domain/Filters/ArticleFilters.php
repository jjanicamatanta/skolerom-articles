<?php

namespace Domain\Filters;

class ArticleFilters extends BaseFilter
{
    private ?int $gradeId;
    private ?string $subjectIds;
    private ?string $coreElementIds;
    private ?string $mainTopicsIds;
    private ?string $goalIds;

    private ?string $source;
    private ?string $lang;
    private ?string $searchTitle;
    private ?string $ids;

    private ?string $order;


    public function __construct()
    {
        $this->gradeIds = null;
        $this->subjectIds = null;
        $this->coreElementIds = null;
        $this->mainTopicsIds = null;
        $this->goalIds = null;
    }

    public function getGradeIds(): ?string
    {
        return $this->gradeIds;
    }

    public function setGradeIds(?string $gradeIds): ArticleFilters
    {
        $this->gradeIds = $gradeIds;
        return $this;
    }

    public function getSubjectIds(): ?string
    {
        return $this->subjectIds;
    }

    public function setSubjectIds(?string $subjectIds): ArticleFilters
    {
        $this->subjectIds = $subjectIds;
        return $this;
    }

    public function getCoreElementIds(): ?string
    {
        return $this->coreElementIds;
    }

    public function setCoreElementIds(?string $coreElementIds): ArticleFilters
    {
        $this->coreElementIds = $coreElementIds;
        return $this;
    }

    public function getMainTopicIds(): ?string
    {
        return $this->mainTopicsIds;
    }

    public function setMainTopicIds(?string $mainTopicsIds): ArticleFilters
    {
        $this->mainTopicsIds = $mainTopicsIds;
        return $this;
    }

    public function getGoalIds(): ?string
    {
        return $this->goalIds;
    }

    public function setGoalIds(?string $goalIds): ArticleFilters
    {
        $this->goalIds = $goalIds;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): ArticleFilters
    {
        $this->source = $source;
        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): ArticleFilters
    {
        $this->lang = $lang;
        return $this;
    }

    public function getSearchTitle(): ?string
    {
        return $this->searchTitle;
    }

    public function setSearchTitle(?string $searchTitle): ArticleFilters
    {
        $this->searchTitle = $searchTitle;
        return $this;
    }

    public function getIds(): ?string
    {
        return $this->ids;
    }

    public function setIds(?string $ids): ArticleFilters
    {
        $this->ids = $ids;
        return $this;
    }


    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function setOrder(?string $order): ArticleFilters
    {
        $this->order = $order;
        return $this;
    }

    public function getSearchQuery(): ?string
    {
        return $this->searchQuery;
    }

    public function setSearchQuery(?string $searchQuery): ArticleFilters
    {
        $this->searchQuery = $searchQuery;

        return $this;
    }
}
