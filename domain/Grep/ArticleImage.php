<?php

namespace Domain\Grep;

class ArticleImage
{
    protected ?int $imgId;
    protected ?string $imgUrl;

    public function __construct()
    {
        $this->imgId = null;
        $this->imgUrl = null;
    }

    public function getImgId(): ?int
    {
        return $this->imgId;
    }

    public function setImgId(?int $imgId): ArticleImage
    {
        $this->imgId = $imgId;
        return $this;
    }

    public function getImgUrl(): ?string
    {
        return is_null($this->imgUrl) ? null : "https://skolerom.no/wp-content/uploads/sites/2/" . $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): ArticleImage
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }
}
