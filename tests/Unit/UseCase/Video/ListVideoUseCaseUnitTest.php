<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\List\DTO\ListVideoInputDto;
use Core\UseCase\Video\List\DTO\ListVideoOutputDto;
use Core\UseCase\Video\List\ListVideoUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListVideoUseCaseUnitTest extends TestCase
{
    public function test_list()
    {
        // Arrange
        $id = Uuid::random();

        $useCase = new ListVideoUseCase(
            repository: $this->mockRepository()
        );

        // Act
        $output = $useCase->exec(
            input: $this->mockInputDTO($id)
        );

        // Assert
        $this->assertInstanceOf(ListVideoOutputDto::class, $output);
    }

    private function mockInputDTO(string $id)
    {
        return Mockery::mock(ListVideoInputDTO::class, [$id]);
    }

    private function mockRepository()
    {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mock->shouldReceive('findById')
            ->once()
            ->andReturn($this->getEntity());

        return $mock;
    }

    private function getEntity(): Entity
    {
        return new Entity(
            title: 'Test',
            description: 'Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
