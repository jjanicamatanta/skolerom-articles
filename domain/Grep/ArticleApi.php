<?php

namespace Domain\Grep;

use App\Mappers\StudentLevelMapper;
use Domain\Article\Repository\ArticleRepositoryInterface;
use Illuminate\Support\Collection;

class ArticleApi
{
    protected ?int $id;
    protected ?string $postTitle;
    protected ?string $postName;
    protected ?string $postType;
    protected ?string $guid;
    protected ?string $postExcerpt;
    protected ?int $termId;
    protected ?string $name;

    protected ?Collection $images;
    protected ?Collection $taxonomies;

    private ArticleRepositoryInterface $articleRepository;


    public function __construct()
    {
        $this->id = null;
        $this->postTitle = null;
        $this->postName = null;
        $this->guid = null;
        $this->postExcerpt = null;
        $this->termId = null;
        $this->name = null;
        $this->articleRepository = resolve(ArticleRepositoryInterface::class);

        $this->images = new Collection();
        $this->taxonomies = new Collection();
        $this->childArticles = new Collection();
    }

    public function loadTaxonomies()
    {
        $this->taxonomies = $this->articleRepository->getTaxonomies($this->id);
    }

    public function loadChildArticles()
    {
        $this->childArticles = $this->articleRepository->getChildArticles($this->id);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): ArticleApi
    {
        $this->id = $id;
        return $this;
    }

    public function getPostTitle(): ?string
    {
        return $this->postTitle;
    }

    public function setPostTitle(?string $postTitle): ArticleApi
    {
        $this->postTitle = $postTitle;
        return $this;
    }

    public function getPostName(): ?string
    {
        return $this->postName;
    }

    public function setPostName(?string $postName): ArticleApi
    {
        $this->postName = $postName;
        return $this;
    }

    public function getPostType(): ?string
    {
        return $this->postType;
    }

    public function setPostType(?string $postType): ArticleApi
    {
        $this->postType = $postType;
        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): ArticleApi
    {
        $this->guid = $guid;
        return $this;
    }

    public function getPostExpert(): ?string
    {
        if (is_null($this->postExcerpt) || $this->postExcerpt == '')
            return $this->articleRepository->getShortDescriptionByArticle($this);

        return $this->postExcerpt;
    }

    public function setPostExpert(?string $postExcerpt): ArticleApi
    {
        $this->postExcerpt = $postExcerpt;
        return $this;
    }

    public function getTermId(): ?int
    {
        return $this->termId;
    }

    public function setTermId(?int $termId): ArticleApi
    {
        $this->termId = $termId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ArticleApi
    {
        $this->name = $name;
        return $this;
    }

    public function getStudentLevels()
    {
        return $this->articleRepository->getStudentLevels($this->taxonomies);
    }

    public function getStudentSubject()
    {
        return $this->articleRepository->getStudentSubject($this->taxonomies);
    }

    public function getStudentGrade()
    {
        return $this->articleRepository->getStudentGrade($this->taxonomies);
    }

    public function getImages(): Collection
    {
        return $this->articleRepository->getImages($this);
        // return $this->images;
    }

    public function getGrepGoals(): Collection
    {
        return $this->articleRepository->getGoalsByArticle($this);
    }

    public function getCoreElements(): Collection
    {
        return $this->articleRepository->getCoreElementsByArticle($this);
    }

    public function getMainTopics(): Collection
    {
        return $this->articleRepository->getMainTopicsByArticle($this);
    }

    public function getStudentSources(): Collection
    {
        // return $this->articleRepository->getStudentSourcesByArticle($this);
        return $this->articleRepository->getStudentSources($this->taxonomies);
    }
    public function getThumbId()
    {
        $thumbIds = session('postMetas', new Collection())->map(function ($postMeta) {
            if ($postMeta->meta_key == '_thumbnail_id' && $postMeta->post_id == $this->id)
                return $postMeta->meta_value;
        });
        $thumbIds = $thumbIds->filter(function ($value) {
            return !is_null($value);
        });
        return $thumbIds->first();
    }

    public function setImages(Collection $images): ArticleApi
    {
        $this->images = $images;
        return $this;
    }

    public function getChildArticles()
    {
        return $this->articleRepository->getChildArticles($this->id);
    }
}
