<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDto;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $listCategoriesUseCase)
    {
        $response = $listCategoriesUseCase->execute(
            input: new ListCategoriesInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('totalPage', 10)
            )
        );


        return CategoryResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => (int) $response->total,
                    'first_page' => $response->first_page,
                    'last_page' => $response->last_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from
                ]
            ]);
    }
}
