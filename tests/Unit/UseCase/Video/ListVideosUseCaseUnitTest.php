<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\ListAll\ListVideosUseCase;
use Core\UseCase\Video\List\DTO\{
    ListVideoInputDto,
    ListVideoOutputDto
};
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;

class ListVideosUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;
    public function test_list_paginate()
    {
        // Arrange

        $useCase = new ListVideosUseCase(
            repository: $this->mockRepository()  
        );

        // Act
       


        // Assert
        $this->assertTrue(true);
    }

    private function mockRepository()
    {
        $mockRepository = Mockery::mock(VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('paginate')
            ->once()
            ->andReturn(
                $this->mockPagination()
            );

        return $mockRepository;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}