<?php

namespace App\Repository\User;

use App\EntityAssemblers\PaginationItemAssembler;
use App\Mappers\AssignmentMapper;
use App\Mappers\GrepArticleMapper;
use App\Models\AssignmentModel;
use App\Models\GradeModel;
use App\Models\GrepGoalRelationModel;
use App\Models\SubjectModel;
use Carbon\Carbon;
use Domain\Users\Repository\Teacher\DistributedAssignmentRepositoryInterface;
use Domain\Users\User;
use Domain\Filters\BaseFilter;
use Domain\Filters\DistributedAssignmentsFilters;
use Domain\Pagination\PaginationCollection;
use Domain\Pagination\PaginationConfig;
use Domain\Users\Repository\GrepGoalRelationRepositoryInterface;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GrepGoalRelationRepository implements GrepGoalRelationRepositoryInterface
{
    private GrepArticleMapper $grepArticleMapper;

    public function __construct(GrepArticleMapper $grepArticleMapper)
    {
        $this->grepArticleMapper = $grepArticleMapper;
    }

    public function getItemsForPage(
        ?User $user,
        ?PaginationConfig $paginationConfig,
        ?BaseFilter $filters
    ): PaginationCollection {
        $articleModelQuery = DB::table('wp_2_posts')
            ->select(
                'wp_2_posts.ID',
                'wp_2_posts.post_title',
                'wp_2_posts.post_name',
                'wp_2_posts.post_type',
                'wp_2_posts.guid',
                'wp_2_posts.post_excerpt',
            )
            ->join('wp_2_grep_post_relations','wp_2_grep_post_relations.post_id','wp_2_posts.ID')
            ->join('vw_grep_all_rule_grade','wp_2_grep_post_relations.goal_relation_id','vw_grep_all_rule_grade.goal_relation_id')
            ->groupBy('wp_2_posts.ID', 'wp_2_posts.post_title', 'wp_2_posts.post_name', 'wp_2_posts.post_type', 'wp_2_posts.guid', 'wp_2_posts.post_excerpt')
            ->orderBy('wp_2_posts.post_date', is_null($filters->getOrder()) ? 'DESC' : $filters->getOrder());

        
        // 
        //Parameters
        if (!is_null($filters->getLang())) {
            $articleModelQuery->whereIn("vw_grep_all_rule_grade.lang_id", explode(',', $filters->getLang()));
        }

        if (!is_null($filters->getSearchTitle())) {
            $searchTitle = is_null($filters->getSearchTitle()) ? '' : $filters->getSearchTitle();
            $articleModelQuery->whereRaw("CONCAT(wp_2_posts.post_title, wp_2_posts.post_content, wp_2_posts.post_name, wp_2_posts.post_excerpt) LIKE '%" . $searchTitle . "%'");
        }

        if (!is_null($filters->getGradeIds()) || !is_null($filters->getSubjectIds()) || !is_null($filters->getCoreElementIds())
            || !is_null($filters->getMainTopicIds()) || !is_null($filters->getGoalIds()) || !is_null($filters->getSource()))
        {
            $filterQuery = DB::table('vw_grep_all_rule_grade as gr')
            ->select('gpr.post_id')
            ->join('wp_2_grep_elements as e_goal', function ($join) { $join->on('gr.goal_id', '=', 'e_goal.element_id')->where('e_goal.element_type','=', 1); })
                ->leftJoin('wp_2_grep_elements_relation as er_main_topic', function ($join) { $join->on('gr.goal_relation_id', '=', 'er_main_topic.goal_relation_id'); })
                    ->leftJoin('wp_2_grep_elements as e_maint_topic', function ($join) { $join->on('er_main_topic.element_id', '=', 'e_maint_topic.element_id')->where('e_maint_topic.element_type', '=', 3); })
                ->leftJoin('wp_2_grep_elements_relation as er_core_element', function ($join) { $join->on('gr.goal_relation_id', '=', 'er_core_element.goal_relation_id'); })
                    ->leftJoin('wp_2_grep_elements as e_core_element', function ($join) { $join->on('er_core_element.element_id', '=', 'e_core_element.element_id')->where('e_core_element.element_type', '=', 2); })
            ->join('wp_2_grep_post_relations as gpr', function ($join) { $join->on('gr.goal_relation_id', '=', 'gpr.goal_relation_id'); })
                ->leftJoin('wp_2_term_relationships as tr_source', function ($join) { $join->on('gpr.post_id', '=', 'tr_source.object_id'); })
                ->leftJoin('wp_2_term_taxonomy as tt_source', function ($join) { $join->on('tt_source.term_taxonomy_id', '=', 'tr_source.term_taxonomy_id')->where('tt_source.taxonomy', '=', 'student_source'); })
                    ->leftJoin('wp_2_grep_elements_group as e_source', function ($join) { $join->on('tt_source.term_id', '=', 'e_source.child_id')->where('e_source.group_type', '=', 3); });
            
            
            //Parameters
            if (!is_null($filters->getLang())) {
                $filterQuery->whereIn("gr.lang_id", explode(',', $filters->getLang()));
            }


            if (!is_null($filters->getGradeIds())) {
                $param_grade_list = explode(",", $filters->getGradeIds());
                $filterQuery->whereIn("gr.grade_id", $param_grade_list);
            }
    
            $filterQuery->groupBy('gpr.post_id');

            if (!is_null($filters->getSubjectIds())) {
                $param_subject_list = explode(",", $filters->getSubjectIds());
    
                foreach ($param_subject_list as $item) {
                    $filterQuery->havingRaw('Sum(Case When gr.subject_id = '.$item.' Then 1 Else 0 End) > 0');
                }
            }
    
            if (!is_null($filters->getCoreElementIds())) {
                $param_core_list = explode(",", $filters->getCoreElementIds());
             
                foreach ($param_core_list as $item) {
                    $filterQuery->havingRaw('Sum(Case When e_core_element.element_id = '.$item.' Then 1 Else 0 End) > 0');
                }
            }
    
            if (!is_null($filters->getMainTopicIds())) {
                $param_dicipline_list = explode(",", $filters->getMainTopicIds());
                
                foreach ($param_dicipline_list as $item) {
                    $filterQuery->havingRaw('Sum(Case When e_maint_topic.element_id = '.$item.' Then 1 Else 0 End) > 0');
                }
            }
    
            if (!is_null($filters->getGoalIds())) {
                $param_goal_list = explode(",", $filters->getGoalIds());
    
                foreach ($param_goal_list as $item) {
                    $filterQuery->havingRaw('Sum(Case When gr.goal_id = '.$item.' Then 1 Else 0 End) > 0');
                }
            }

            if (!is_null($filters->getSource())) {
                $param_source_list = explode(",", $filters->getSource());
    
                foreach ($param_source_list as $item) {
                    $filterQuery->havingRaw('Sum(Case When e_source.group_id = '.$item.' Then 1 Else 0 End) > 0');
                }
            }

            $postFilteredIds = $filterQuery->get()->pluck('post_id');

            if (!is_null($postFilteredIds)) {
                $articleModelQuery->whereIn("wp_2_posts.ID", $postFilteredIds);
            }
        }

        $articleModelQueryForPagination = clone $articleModelQuery;

        // dd($articleModelQuery->first());

        $total = $articleModelQueryForPagination->get()->count();

        $articleModels = $articleModelQueryForPagination
            ->offset($paginationConfig->calculateOffset())
            ->limit($paginationConfig->getPerPage());

        $articleModels = $articleModels->get();

        $thumbnailIds = DB::table('wp_2_postmeta')
            ->whereIn('wp_2_postmeta.post_id', $articleModels->pluck('ID'))
            ->where('wp_2_postmeta.meta_key', '_thumbnail_id')
            ->get()
            ->pluck('meta_value');

        $postMetas = DB::table('wp_2_postmeta')
            ->where(function ($query) use ($thumbnailIds, $articleModels) {
                $query
                    ->orWhereIn('wp_2_postmeta.post_id', $thumbnailIds)
                    ->orWhereIn('wp_2_postmeta.post_id', $articleModels->pluck('ID'))
                    ->orWhere(function ($query) use ($thumbnailIds) {
                        $query->where('wp_2_postmeta.meta_key', '_thumbnail_id')
                            ->whereIn('wp_2_postmeta.meta_value', $thumbnailIds);
                    });
            })
            ->whereIn('wp_2_postmeta.meta_key', [
                'txtGREPSectionJSONText',
                '_wp_attached_file',
                '_thumbnail_id',
                'education_article_settings_short_description',
                '_wp_attachment_metadata'
            ])
            ->get();

        session(['postMetas' => $postMetas]);

        return PaginationItemAssembler::createEntityFromCollection(collect([
            'items' => $this->grepArticleMapper->mapToCollectionOfEntitiesFromEloquent($articleModels),
            'total' => $total
        ]));
    }

    private function applyFilters(
        $filters,
        $assignmentModelQuery,
        int $userId
    ): Builder {
        return $assignmentModelQuery;
        $order = $filters->getOrder();
        $orderField = $filters->getOrderField();

        $orderedAssignmentModelQuery = GrepGoalRelationModel::query()
            ->fromSub($assignmentModelQuery, 'assignments');

        if ($order && $orderField) {
            switch ($orderField) {
                case 'deadline':
                    $orderedAssignmentModelQuery
                        ->orderBy('assignments.default_end_date', $order);
                    break;
                default:
                    break;
            }
        }

        return $orderedAssignmentModelQuery;
    }
}
