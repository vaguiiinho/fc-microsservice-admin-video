<?php

namespace App\Http\Controllers\Api;

use App\Adapters\ApiAdapter;
use App\Http\Controllers\Controller;
use App\Http\Requests\{
    StoreVideoRequest,
    UpdateVideoRequest
};
use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\{
    CreateVideoUseCase,
    DTO\CreateInputVideoDTO
};
use Core\UseCase\Video\Delete\{
    DeleteVideoUseCase,
    DTO\DeleteVideoInputDto
};
use Core\UseCase\Video\List\{
    ListVideoUseCase,
    DTO\ListVideoInputDto
};
use Core\UseCase\Video\Paginate\{
    ListVideosUseCase,
    DTO\PaginateVideosInputDto
};
use Core\UseCase\Video\Update\{
    UpdateVideoUseCase,
    DTO\UpdateInputVideoDTO
};
use Illuminate\Http\{
    Request,
    Response
};

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

        return (new ApiAdapter($response))->toJson();
    }

    public function show(ListVideoUseCase $UseCase, $id)
    {
        $response = $UseCase->exec(input: new ListVideoInputDto($id));
        return ApiAdapter::json($response);
    }

    public function store(CreateVideoUseCase $useCase, StoreVideoRequest $request)
    {
        $response = $useCase->exec(new CreateInputVideoDTO(
            title: $request->title,
            description: $request->description,
            yearLaunched: $request->year_launched,
            duration: $request->duration,
            opened: $request->opened,
            rating: Rating::from($request->rating),
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: getArrayFile($request->file('video_file')),
            trailerFile: getArrayFile($request->file('trailer_file')),
            bannerFile: getArrayFile($request->file('banner_file')),
            thumbFile: getArrayFile($request->file('thumb_file')),
            thumbHalf: getArrayFile($request->file('thumb_half_file')),
        ));

        return ApiAdapter::json($response, Response::HTTP_CREATED);
    }

    public function update(UpdateVideoUseCase $useCase, UpdateVideoRequest $request, $id)
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

        $response = $useCase->exec(
            input: new UpdateInputVideoDTO(
                id: $id,
                title: $request->title,
                description: $request->description,
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

        return ApiAdapter::json($response);
    }

    public function destroy(DeleteVideoUseCase $useCase, $id)
    {
        $useCase->execute(new DeleteVideoInputDto(id: $id));

        return response()->noContent();
    }
}
