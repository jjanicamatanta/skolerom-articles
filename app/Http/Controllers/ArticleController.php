<?php

namespace App\Http\Controllers;

use App\EntityAssemblers\ArticleFiltersAssembler;
use App\EntityAssemblers\PaginationConfigAssembler;
use App\Mappers\GrepArticleMapper;
use App\Models\PostModel;
use App\Transformers\ArticleChildTransformer;
use App\Transformers\ArticleListTransformer;
use Domain\Service\Articles\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends ApiController
{
    private ArticleService $articleService;
    private GrepArticleMapper $articleMapper;


    public function __construct(
        ArticleService $articleService,
        GrepArticleMapper $articleMapper
    ) {
        $this->articleService = $articleService;
        $this->articleMapper = $articleMapper;
    }

    public function index(Request $request)
    {
        // DB::enableQueryLog();
        $paginationConfig = PaginationConfigAssembler::createEntityFromHttp(collect($request->all()));
        $filtersConfig = ArticleFiltersAssembler::createEntityFromHttp(collect($request->all()));;
        // $var = $this->transformPaginatedCollectionResponse(
        //     $this->articleService->getAllArticles($paginationConfig, $filtersConfig),
        //     new ArticleListTransformer(),
        //     $request
        // );

        // dd(DB::getQueryLog(),(collect(DB::getQueryLog())->sum('time')));

        return response()->json(
            $this->transformPaginatedCollectionResponse(
                $this->articleService->getAllArticles($paginationConfig, $filtersConfig),
                new ArticleListTransformer(),
                $request
            )
        );
    }


    public function getChildArticles(Request $request, $postId)
    {
        $article = $this->articleMapper->mapToEntityFromEloquent(PostModel::findOrFail($postId));
        return $this->articleService->getChilds($article);
    }
}
