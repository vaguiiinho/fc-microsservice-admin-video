<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\DTO\PaginateVideosInputDto;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Tests\TestCase;

class ListVideosUseCaseTest extends TestCase
{
    public function test_pagination()
    {

        // Arrange

        Video::factory()->count(50)->create();

        $useCase = new ListVideosUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        // Act

        $response = $useCase->exec(new PaginateVideosInputDto());

        // Assert

        $this->assertCount(15, $response->items);
        $this->assertEquals(50, $response->total);
    }
}
