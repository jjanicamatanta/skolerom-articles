<?php

namespace Domain\Grep;

class GrepElement
{
    protected ?string $kode;
    protected ?string $description;

    public function __construct()
    {
        $this->kode = null;
        $this->description = null;
    }

    public function getKode(): ?string
    {
        return $this->kode;
    }

    public function setKode(?string $kode): GrepElement
    {
        $this->kode = $kode;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): GrepElement
    {
        $this->description = $description;
        return $this;
    }
}
