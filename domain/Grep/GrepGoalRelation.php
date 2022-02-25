<?php

namespace Domain\Grep;

class GrepGoalRelation
{
    protected ?int $goalRelationId;
    protected ?int $goalId;
    protected ?string $kode;
    protected ?int $subjectId;
    protected ?int $gradeId;
    protected ?int $langId;

    public function __construct()
    {
        $this->goalRelationId = null;
        $this->goalId = null;
        $this->kode = null;
        $this->subjectId = null;
        $this->gradeId = null;
        $this->langId = null;
    }

    public function getGoalRelationId(): ?int
    {
        return $this->goalRelationId;
    }

    public function setGoalRelationId(?int $goalRelationId): GrepGoalRelation
    {
        $this->goalRelationId = $goalRelationId;
        return $this;
    }

    public function getGoalId(): ?int
    {
        return $this->goalId;
    }

    public function setGoalId(?int $goalId): GrepGoalRelation
    {
        $this->goalId = $goalId;
        return $this;
    }

    public function getKode(): ?string
    {
        return $this->kode;
    }

    public function setKode(?string $kode): GrepGoalRelation
    {
        $this->kode = $kode;
        return $this;
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    public function setSubjectId(?int $subjectId): GrepGoalRelation
    {
        $this->subjectId = $subjectId;
        return $this;
    }

    public function getGradeId(): ?int
    {
        return $this->gradeId;
    }

    public function setGradeId(?int $gradeId): GrepGoalRelation
    {
        $this->gradeId = $gradeId;
        return $this;
    }

    public function getLangId(): ?int
    {
        return $this->langId;
    }

    public function setLangId(?int $langId): GrepGoalRelation
    {
        $this->langId = $langId;
        return $this;
    }
}
