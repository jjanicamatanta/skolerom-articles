<?php

namespace App\Transformers;

use Domain\Grep\ArticleApi;
use Domain\Grep\ArticleImage;
use Domain\Grep\GrepGoalRelation;

use League\Fractal\TransformerAbstract;

class ArticleListTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'images',
        'student_level',
        'student_subject',
        'student_grade',
        'grep_goals',
        'grep_coreelements',
        'grep_maintopic',
        'student_source',
        // 'childArticles'
    ];
    
    
    public static function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        // $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function transform(ArticleApi $articleApi): array
    {
        $images = $articleApi->getImages()->first();
    
        $protocol=$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];

        return [
            'excerpt' => $articleApi->getPostExpert(),
            'title' => $articleApi->getPostTitle(),
            'id' => strval($articleApi->getId()),
            'link' => $protocol.'://'.$host. '/undervisning/' . $articleApi->getPostName(),
            'guid' => $articleApi->getGuid(),
            'post_type' => $articleApi->getPostType(),
        ];
    }

    public function includeStudentLevel(ArticleApi $articleApi)
    {
        $stundetLevels = $articleApi->getStudentLevels();
        return $this->collection($stundetLevels, new StudentLevelTransformer($articleApi));
    }

    public function includeStudentSubject(ArticleApi $articleApi)
    {
        $stundetLevels = $articleApi->getStudentSubject();
        return $this->collection($stundetLevels, new StudentSubjectTransformer());
    }

    public function includeStudentGrade(ArticleApi $articleApi)
    {
        $stundetLevels = $articleApi->getStudentGrade();
        return $this->collection($stundetLevels, new StudentSubjectTransformer());
    }

    public function includeGrepGoals(ArticleApi $articleApi)
    {
        $stundetLevels = $articleApi->getGrepGoals();
        return $this->collection($stundetLevels, new GrepElementTransformer());
    }

    public function includeGrepCoreelements(ArticleApi $articleApi)
    {
        $stundetLevels = $articleApi->getCoreElements();
        return $this->collection($stundetLevels, new GrepElementTransformer());
    }

    public function includeGrepMaintopic(ArticleApi $articleApi)
    {
        $stundetLevels = $articleApi->getMainTopics();
        return $this->collection($stundetLevels, new GrepElementTransformer());
    }

    public function includeStudentSource(ArticleApi $articleApi)
    {
        $stundetLevels = $articleApi->getStudentSources();
        return $this->collection($stundetLevels, new StudentSubjectTransformer());
    }

    public function includeImages(ArticleApi $articleApi)
    {
        $image = $articleApi->getImages()->first();

        if (is_null($image)) {
            $image = new ArticleImage();
            $image->setImgId(0);
            $image->setImgUrl(null);
        }

        return $this->item($image, new ArticleImageTransformer());
    }

    // public function includeChildArticles(ArticleApi $articleApi)
    // {
    //     $childArticles = $articleApi->getChildArticles();
    //     if(count($childArticles) > 0) {
    //         dd($childArticles);
    //     }
    //     return $this->collection($childArticles, new GrepElementTransformer());
    // }
}
