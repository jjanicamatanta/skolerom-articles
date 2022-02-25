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

        $filterQuery = DB::table('list_grep_all4 as gr')
            ->select('gpr.post_id')
            ->leftJoin('wp_2_grep_elements as e_goal', function ($join) {
                $join->on('gr.goal_id', '=', 'e_goal.element_id')
                    ->where('e_goal.element_type', '1');
            })
            ->leftJoin('wp_2_grep_elements_relation as er_main_topic', function ($join) {
                $join->on('gr.goal_relation_id', '=', 'er_main_topic.goal_relation_id');
            })
            ->leftJoin('wp_2_grep_elements as e_maint_topic', function ($join) {
                $join->on('er_main_topic.element_id', '=', 'e_maint_topic.element_id')
                    ->where('e_maint_topic.element_type', 3);
            })
            ->leftJoin('wp_2_grep_elements_relation as er_core_element', function ($join) {
                $join->on('gr.goal_relation_id', '=', 'er_core_element.goal_relation_id');
            })
            ->leftJoin('wp_2_grep_elements as e_core_element', function ($join) {
                $join->on('er_core_element.element_id', '=', 'e_core_element.element_id')
                    ->where('e_core_element.element_type', 2);
            })
            ->leftJoin('wp_2_grep_post_relations as gpr', function ($join) {
                $join->on('gr.goal_relation_id', '=', 'gpr.goal_relation_id');
            })
            ->groupBy('gpr.post_id');

        if (!is_null($filters->getIds())) {
            $filterQuery
                ->whereIn('gpr.post_id', $filters->getIds());
        }

        //

        if (!is_null($filters->getGradeId())) {
            $filterQuery->where('gr.grade_id', $filters->getGradeId());
        }

        if (!is_null($filters->getSubjectIds())) {
            $param_subject_list = explode(",", $filters->getSubjectIds());

            $filterQuery->whereIn("gr.subject_id", $param_subject_list);
        }

        if (!is_null($filters->getCoreElementIds())) {
            $param_core_list = explode(",", $filters->getCoreElementIds());
            $filterQuery->whereIn("e_core_element.element_id", $param_core_list);
        }

        if (!is_null($filters->getMainTopicIds())) {
            $param_dicipline_list = explode(",", $filters->getMainTopicIds());
            $filterQuery->whereIn("e_maint_topic.element_id", $param_dicipline_list);
        }

        if (!is_null($filters->getGoalIds())) {
            $param_goal_list = explode(",", $filters->getGoalIds());
            $filterQuery->whereIn("gr.goal_id", $param_goal_list);
        }

        // 

        $postFilteredIds = $filterQuery->get()->pluck('post_id');

        $articleModelQuery = DB::table('wp_2_posts')
            ->select(
                'wp_2_posts.ID',
                'wp_2_posts.post_title',
                'wp_2_posts.post_type',
                'wp_2_posts.guid',
                'wp_2_posts.post_excerpt',
            )
            ->where('wp_2_posts.post_status', 'publish')
            ->where('wp_2_posts.post_type', 'education')
            ->whereIn('wp_2_posts.ID', $postFilteredIds)

            ->groupBy('wp_2_posts.ID', 'wp_2_posts.post_title', 'wp_2_posts.post_type', 'wp_2_posts.guid', 'wp_2_posts.post_excerpt')
            ->orderBy('wp_2_posts.ID', is_null($filters->getOrder()) ? 'DESC' : $filters->getOrder());

        if (!is_null($filters->getLang())) {
            $articleModelQuery->join('wp_2_term_relationships as tt0', 'wp_2_posts.ID', '=', 'tt0.object_id');
            $articleModelQuery->where('tt0.term_taxonomy_id', $filters->getLang());
        }

        if (!is_null($filters->getSource())) {
            $articleModelQuery->join('wp_2_term_relationships as tt_source', 'wp_2_posts.ID', '=', 'tt_source.object_id');
            $articleModelQuery->whereIn('tt_source.term_taxonomy_id', explode(',', $filters->getSource()));
        };

        $articleModelQueryForPagination = clone $articleModelQuery;

        $total = $articleModelQueryForPagination->get()->count();

        $articleModels = $articleModelQueryForPagination
            ->offset($paginationConfig->calculateOffset())
            ->limit($paginationConfig->getPerPage());


        $articleModelIds = $articleModelQueryForPagination->get()->pluck('ID');

        $postMetasToFilterArticle = DB::table('wp_2_postmeta')
            ->whereIn('wp_2_postmeta.post_id', $articleModelIds)
            ->where('wp_2_postmeta.meta_key', 'main_article')
            ->where('wp_2_postmeta.meta_value', '1')
            ->get();

        $newIds = $postMetasToFilterArticle->pluck('post_id');

        $articleModels = (clone $articleModelQuery)
            ->whereIn('wp_2_posts.ID', $newIds->toArray()); // <-- new

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
            ->whereIn('wp_2_postmeta.meta_key', ['txtGREPSectionJSONText', '_wp_attached_file', '_thumbnail_id', 'niva_1', 'niva_2', 'niva_3'])
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
