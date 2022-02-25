<?php

namespace App\Http\Controllers;

use Domain\Pagination\Pagination;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\TransformerAbstract;
use Spatie\Fractal\Facades\Fractal;

abstract class ApiController extends Controller
{
    protected function transformCollectionResponse($collection, TransformerAbstract $transformer)
    {
        return Fractal::create()
            ->collection($collection, $transformer, 'data')
            ->toArray();
    }

    protected function transformItemResponse($item, TransformerAbstract $transformer)
    {
        return Fractal::create()
            ->item($item)
            ->transformWith($transformer)
            ->toArray();
    }

    protected function transformPaginatedCollectionResponse(Pagination $pagination, TransformerAbstract $transformer, Request $request)
    {
        $paginator = new LengthAwarePaginator(
            $pagination->getItems(),
            $pagination->getTotalItems(),
            $pagination->getPerPage(),
            $pagination->getCurrentPage(),
            [
                'query' => $request->query(),
            ]);

        return Fractal::create()
            ->collection($pagination->getItems(), $transformer, 'data')
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();
    }
}
