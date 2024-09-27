<?php

namespace Tests\Unit\UseCase\Video;


use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\ListVideosUseCase;
use Core\UseCase\Video\Paginate\DTO\{
    PaginateVideosInputDto,
    PaginateVideosOutputDto
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
        $response = $useCase->exec(
            input: $this->mockInputDto()
        );

        // Assert
        $this->assertInstanceOf(PaginateVideosOutputDto::class, $response);
    }

    private function mockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('paginate')
            ->once()
            ->andReturn(
                $this->mockPagination()
            );

        return $mockRepository;
    }

    private function mockInputDto()
    {
        return Mockery::mock(PaginateVideosInputDto::class, [
            '',
            'DESC',
            1,
            15,
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
