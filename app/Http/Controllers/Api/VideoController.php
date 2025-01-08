<?php

namespace App\Http\Controllers\Api;

use App\Adapters\ApiAdapter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Core\UseCase\Video\Delete\DTO\DeleteVideoInputDto;
use Core\UseCase\Video\List\DTO\ListVideoInputDto;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\UseCase\Video\Paginate\DTO\PaginateVideosInputDto;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Core\UseCase\Video\Update\DTO\UpdateInputVideoDTO;
use Core\UseCase\Video\Update\UpdateVideoUseCase;
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
        $response = $useCase->exec(
            input: new UpdateInputVideoDTO(
                id: $id,
                title: $request->title,
                description: $request->description,
                categories: $request->categories,
                genres: $request->genres,
                castMembers: $request->cast_members,
                videoFile: getArrayFile($request->file('video_file')),
                trailerFile: getArrayFile($request->file('trailer_file')),
                bannerFile: getArrayFile($request->file('banner_file')),
                thumbFile: getArrayFile($request->file('thumb_file')),
                thumbHalf: getArrayFile($request->file('thumb_half_file')),
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
