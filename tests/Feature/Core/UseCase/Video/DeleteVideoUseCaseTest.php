<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Core\UseCase\Video\Delete\DTO\DeleteVideoInputDto;
use Tests\TestCase;

class DeleteVideoUseCaseTest extends TestCase
{
    public function test_delete()
    {
        $video = Video::factory()->create();

        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class),
        );

        $response = $useCase->execute(new DeleteVideoInputDto(id: $video->id));

        $this->assertTrue($response->success);
    }

    public function test_delete_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Video not found');

        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class),
        );

        $useCase->execute(new DeleteVideoInputDto('fake_id'));
    }
}
