<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\List\{
    ListVideoUseCase,
    DTO\ListVideoInputDto
};
use Core\UseCase\Video\Paginate\{
    ListVideosUseCase,
    DTO\PaginateVideosInputDto
};
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideoController extends Controller
{
    public function index(Request $request, ListVideosUseCase $UseCase)
    {
        $response = $UseCase->exec(
            input: new PaginateVideosInputDto(
                filter: $request->filter ?? '',
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page'),
                totalPage: (int) $request->get('totalPage')
            )
        );

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

    public function show(ListVideoUseCase $UseCase, $id)
    {
        $response = $UseCase->exec(
            input: new ListVideoInputDto($id)
        );
        return new VideoResource($response);
    }

    public function store(CreateVideoUseCase $UseCase, Request $request)
    {
        if ($file = $request->file('video_file')) {
            $videoFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'type' => $file->getType(),
                'size' => $file->getSize(),
                'error' => $file->getError()
            ];
        }

        if ($file = $request->file('trailer_file')) {
            $trailer = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'type' => $file->getType(),
                'size' => $file->getSize(),
                'error' => $file->getError()
            ];
        }

        if ($file = $request->file('banner_file')) {
            $bannerFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'type' => $file->getType(),
                'size' => $file->getSize(),
                'error' => $file->getError()
            ];
        }

        if ($file = $request->file('thumb_file')) {
            $thumbFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'type' => $file->getType(),
                'size' => $file->getSize(),
                'error' => $file->getError()
            ];
        }

        if ($file = $request->file('thumb_half_file')) {
            $thumb_halfFile = [
                'name' => $file->getClientOriginalName(),
                'tmp_name' => $file->getPathname(),
                'type' => $file->getType(),
                'size' => $file->getSize(),
                'error' => $file->getError()
            ];
        }

        $response = $UseCase->exec(
            input: new CreateInputVideoDTO(
                title: $request->title,
                description: $request->description,
                yearLaunched: $request->year_launched,
                duration: $request->duration,
                opened: $request->opened,
                rating: Rating::from($request->rating),
                categories: $request->categories,
                genres: $request->genres,
                castMembers: $request->cast_members,
                videoFile: $videoFile ?? null,
                trailerFile: $trailer ?? null,
                bannerFile: $bannerFile ?? null,
                thumbFile: $thumbFile ?? null,
                thumbHalf: $thumb_halfFile ?? null,
            )
        );

        return (new VideoResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
