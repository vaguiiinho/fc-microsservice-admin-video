<?php

namespace Tests\Feature\Core\UseCase\Video\Create;

use App\Models\Video;
use Core\UseCase\Video\Update\DTO\UpdateInputVideoDTO;
use Core\UseCase\Video\Update\UpdateVideoUseCase;
use Tests\Feature\Core\UseCase\Video\BaseVideoUseCase;

class UpdateVideoUseCaseTest extends BaseVideoUseCase
{
    public function useCase(): string
    {
        return UpdateVideoUseCase::class;
    }
    public function inputDto(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMemberIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
    ): object {
        $video = Video::factory()->create();

        return new UpdateInputVideoDTO(
            id: $video->id,
            title: 'Test Video',
            description: 'Test Description',
            categories: $categoriesIds,
            genres: $genresIds,
            castMembers: $castMemberIds,
            videoFile: $videoFile,
            trailerFile: $trailerFile,
            thumbFile: $thumbFile,
            thumbHalf: $thumbHalf,
            bannerFile: $bannerFile,
        );
    }
}
