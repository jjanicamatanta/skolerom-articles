<?php

namespace App\Repository\Article;

use App\Mappers\ArticleImageMapper;
use App\Mappers\GrepElementMapper;
use App\Mappers\StudentLevelMapper;
use Domain\Article\Repository\ArticleRepositoryInterface;
use Domain\Grep\ArticleApi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ArticleRepository implements ArticleRepositoryInterface
{

    private StudentLevelMapper $studentLevelMapper;
    private ArticleImageMapper $articleImageMapper;
    private GrepElementMapper $grepElementMapper;

    public function __construct(
        StudentLevelMapper $studentLevelMapper,
        ArticleImageMapper $articleImageMapper,
        GrepElementMapper $grepElementMapper
    ) {
        $this->studentLevelMapper = $studentLevelMapper;
        $this->articleImageMapper = $articleImageMapper;
        $this->grepElementMapper = $grepElementMapper;
    }

    public function getTaxonomies(int $objectId)
    {
        return DB::table('wp_2_term_relationships')
            ->select('*')
            ->join('wp_2_term_taxonomy', 'wp_2_term_taxonomy.term_taxonomy_id', 'wp_2_term_relationships.term_taxonomy_id')
            ->join('wp_2_terms', 'wp_2_terms.term_id', 'wp_2_term_taxonomy.term_id')
            ->whereIn('wp_2_term_taxonomy.taxonomy', ['student_level', 'student_subject', 'student_grade', 'student_source'])
            ->where('wp_2_term_relationships.object_id', $objectId)
            ->get();
    }

    public function getChildArticles(int $objectId)
    {
        // 40933
        $childPostIds = DB::table('wp_2_posts as post')
            ->select('postmeta.meta_value')
            ->join('wp_2_postmeta as postmeta', 'postmeta.post_id', '=', 'post.ID')
            ->where('post.ID', $objectId)
            ->whereIn('postmeta.meta_key', ['niva_1', 'niva_2', 'niva_3']);


        $query = DB::table('wp_2_term_relationships as relationship')
            ->selectRaw('
                post.ID id,	
                post.post_title title,
                GROUP_CONCAT(terms.term_id," | ",terms.name," | ",terms.slug ) as level
            ')
            ->join('wp_2_term_taxonomy as taxonomy', 'taxonomy.term_taxonomy_id', '=', 'relationship.term_taxonomy_id')
            ->join('wp_2_terms as terms', 'terms.term_id', '=', 'taxonomy.term_id')
            ->join('wp_2_posts as post', 'post.ID', '=', 'relationship.object_id')
            ->where('taxonomy.taxonomy', 'student_level')
            ->whereIn('relationship.object_id', $childPostIds)
            ->groupBy('post.ID', 'post.post_title')
            ->get();

        return $query->map(function ($item) {
            $levelFields = explode('|', $item->level);
            $level = json_decode(json_encode([
                'term_id' => intval(trim($levelFields[0])) ,
                'name' => trim($levelFields[1]),
                'slug' => trim($levelFields[2]),
            ]), false);
            $item->level = $level;
            return $item;
        });
    }


    public function getGoalsByArticle(ArticleApi $article)
    {
        return $this->getGrepElementsByArticle($article)['goals'];
    }

    public function getCoreElementsByArticle(ArticleApi $article)
    {
        return $this->getGrepElementsByArticle($article)['coreElements'];
    }

    public function getMainTopicsByArticle(ArticleApi $article)
    {
        return $this->getGrepElementsByArticle($article)['mainTopics'];
    }

    public function getStudentSourcesByArticle(ArticleApi $article)
    {
        return null;
    }

    public function getGrepElementsByArticle(ArticleApi $article)
    {
        $articleId = $article->getId();
        $grepGoalsArr = new Collection();
        $studentDisciplin = new Collection();
        $grepCoreelementsArr = new Collection();
        session('postMetas', new Collection())->map(function ($postMeta) use ($articleId, $grepGoalsArr, $studentDisciplin, $grepCoreelementsArr) {
            if ($postMeta->meta_key == 'txtGREPSectionJSONText' && $postMeta->post_id == $articleId) {

                $grepJsonArr = json_decode(strval($postMeta->meta_value), true);

                if (array_key_exists('grade_selected', $grepJsonArr)) {
                    $grepGradeSelectedArr = $grepJsonArr['grade_selected'];
                    if (is_array($grepGradeSelectedArr)) {
                        foreach ($grepGradeSelectedArr as $grade_selected_item) {
                            foreach ($grade_selected_item['grades_subject'] as $grades_subject_item) {
                                foreach ($grades_subject_item['objetivies'] as $objetiviesItem) {
                                    if (!in_array($objetiviesItem['description'], $grepGoalsArr->toArray())) {
                                        $grepGoalsArr->push(json_decode(json_encode($objetiviesItem), false));
                                    }
                                }
                            }
                        }
                    }
                    // dd($grepJsonArr);
                    if (is_array($grepJsonArr['maintopic_filter'])) {

                        (new Collection($grepJsonArr['maintopic_filter']))->map(function ($coreElement) use ($studentDisciplin) {
                            $studentDisciplin->push(json_decode(json_encode($coreElement), false));
                        });
                    }

                    if (is_array($grepJsonArr['coreelements_filter'])) {

                        (new Collection($grepJsonArr['coreelements_filter']))->map(function ($coreElement) use ($grepCoreelementsArr) {
                            $grepCoreelementsArr->push(json_decode(json_encode($coreElement), false));
                        });
                    }
                }
            }
        });

        return [
            'goals' => $this->grepElementMapper->mapToCollectionOfEntitiesFromEloquent($grepGoalsArr),
            'coreElements' => $this->grepElementMapper->mapToCollectionOfEntitiesFromEloquent($grepCoreelementsArr),
            'mainTopics' => $this->grepElementMapper->mapToCollectionOfEntitiesFromEloquent($studentDisciplin),
        ];
    }

    public function getImages(ArticleApi $article)
    {

        $thumbId = $article->getThumbId();

        $imagesModel = session('postMetas', new Collection())->map(function ($postMeta) use ($thumbId) {
            if ($postMeta->meta_key == '_wp_attached_file' && $postMeta->post_id == $thumbId)
                return $postMeta;
        });
        $imagesModel = $imagesModel->filter(function ($value) {
            return !is_null($value);
        });;

        return $this->articleImageMapper->mapToCollectionOfEntitiesFromEloquent($imagesModel);

        // $thumb = DB::table('wp_2_postmeta')
        //     ->where('post_id', $articleId)
        //     ->where('meta_key', '_thumbnail_id')
        //     ->first();

        // if (is_null($thumb))
        //     return new Collection();

        // $thumbId = $thumb->meta_value;

        // $imagesModel = DB::table('wp_2_postmeta')
        //     ->select('wp_2_postmeta.meta_value  as img_url', 'wp_2_postmeta.post_id as img_id')
        //     ->where('post_id', $thumbId)
        //     ->where('meta_key', '_wp_attached_file')
        //     ->get();


        // return $this->articleImageMapper->mapToCollectionOfEntitiesFromEloquent($imagesModel);
    }

    public function getStudentLevels($taxonomies)
    {
        $studentLevelTaxonomies = $taxonomies->filter(function ($taxonomy) {
            if ($taxonomy->taxonomy == 'student_level')
                return $taxonomy;
        });

        return
            $this->studentLevelMapper->mapToCollectionOfEntitiesFromEloquent($studentLevelTaxonomies);
    }


    public function getStudentSources($taxonomies)
    {
        $studentLevelTaxonomies = $taxonomies->filter(function ($taxonomy) {
            if ($taxonomy->taxonomy == 'student_source')
                return $taxonomy;
        });

        return
            $this->studentLevelMapper->mapToCollectionOfEntitiesFromEloquent($studentLevelTaxonomies);
    }

    public function getStudentSubject($taxonomies)
    {
        $studentLevelTaxonomies = $taxonomies->filter(function ($taxonomy) {
            if ($taxonomy->taxonomy == 'student_subject')
                return $taxonomy;
        });

        return
            $this->studentLevelMapper->mapToCollectionOfEntitiesFromEloquent($studentLevelTaxonomies);
    }


    public function getStudentGrade($taxonomies)
    {
        $studentLevelTaxonomies = $taxonomies->filter(function ($taxonomy) {
            if ($taxonomy->taxonomy == 'student_grade')
                return $taxonomy;
        });

        return
            $this->studentLevelMapper->mapToCollectionOfEntitiesFromEloquent($studentLevelTaxonomies);
    }




    // _thumbnail_id || wp_2_postmeta


}
