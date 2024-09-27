<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\UseCase\Video\List\DTO\{
    ListVideoInputDto,
    ListVideoOutputDto
};
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListVideoUseCaseUnitTest extends TestCase
{
    public function test_list()
    {
        // Arrange
        $uuid = Uuid::random();

        $useCase = new ListVideoUseCase(
            repository: $this->mockRepository()
        );

        // Act
        $output = $useCase->exec(
            input: $this->mockInputDTO(
                $uuid
            )
        );


        // Assert
        $this->assertInstanceOf(ListVideoOutputDto::class, $output);
    }

    private function mockInputDTO()
    {
        return Mockery::mock(ListVideoInputDTO::class);
    }

    private function mockRepository()
    {
        return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
