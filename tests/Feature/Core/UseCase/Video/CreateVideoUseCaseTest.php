<?php

namespace Tests\Feature\Core\UseCase\Video\Create;

use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Support\Facades\Event;
use Tests\Feature\Core\UseCase\Video\BaseVideoUseCase;
use Throwable;

class CreateVideoUseCaseTest extends BaseVideoUseCase
{
    public function useCase(): string
    {
        return CreateVideoUseCase::class;
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
        return new CreateInputVideoDTO(
            title: 'Test Video',
            description: 'Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
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

    /**
     * @test
     */
    public function transactionExcepition()
    {
        Event::listen(TransactionBeginning::class, function () {
            throw new Exception('Begin transaction');
        });

        try {
            $sut = $this->makeSut();
            $input = $this->inputDto();
            $sut->exec($input);
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
        }
    }
}
