<?php

namespace App\Repository\Article;

use App\Mappers\StudentLevelMapper;
use Domain\Article\Repository\StudentLevelRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StudentLevelRepository implements StudentLevelRepositoryInterface
{

    private StudentLevelMapper $studentLevelMapper;

    public function __construct(StudentLevelMapper $studentLevelMapper)
    {
        $this->studentLevelMapper = $studentLevelMapper;
    }

    public function getStudentLevels(int $articleId)
    {
        $query = DB::table('wp_2_term_relationships')
            ->select('wp_2_terms.term_id', 'wp_2_terms.name', 'wp_2_terms.slug')
            ->join('wp_2_term_taxonomy', 'wp_2_term_taxonomy.term_taxonomy_id', 'wp_2_term_relationships.term_taxonomy_id')
            ->join('wp_2_terms', 'wp_2_terms.term_id', 'wp_2_term_taxonomy.term_id')
            ->where('wp_2_term_taxonomy.taxonomy', 'student_level')
            ->where('wp_2_term_relationships.object_id', $articleId)
            ->get();

        return $this->studentLevelMapper->mapToCollectionOfEntitiesFromEloquent($query);
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
}
