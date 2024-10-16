<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\UseCase\Video\List\DTO\ListVideoInputDto;
use Tests\TestCase;

class ListVideoUseCaseTest extends TestCase
{
    public function test_list()
    {
        $video = Video::factory()->create();

        $useCase = new ListVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class),
        );

        $response = $useCase->exec(new ListVideoInputDto(id: $video->id));

        $this->assertEquals($video->id, $response->id);
        $this->assertEquals($video->title, $response->title);
        $this->assertEquals($video->description, $response->description);
    }
}
