<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Core\UseCase\Video\Paginate\DTO\PaginateVideosInputDto;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request, ListVideosUseCase $UseCase)
    {
        $response = $UseCase->exec(
            input: new PaginateVideosInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('totalPage')
            )
        );

        dd($response->items);

        return VideoResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => (int) $response->total,
                    'current_page' => $response->current_page,
                    'first_page' => $response->first_page,
                    'last_page' => $response->last_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from
                ]
            ]);
    }
}
