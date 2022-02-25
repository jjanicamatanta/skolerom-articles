<?php

namespace Domain\Article\Repository;

use Domain\Grep\ArticleApi;

interface ArticleRepositoryInterface
{
    public function getImages(ArticleApi $article);
    
    public function getGoalsByArticle(ArticleApi $article);

    public function getCoreElementsByArticle(ArticleApi $article);
    
    public function getMainTopicsByArticle(ArticleApi $article);

    public function getStudentSources($taxonomies);

    public function getTaxonomies(int $objectId);

    public function getChildArticles(int $objectId);

    public function getStudentLevels($taxonomies);
    
    public function getStudentSubject($taxonomies);
    
    public function getStudentGrade($taxonomies);

    
}
