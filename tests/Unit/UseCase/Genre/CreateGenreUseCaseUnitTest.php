<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\DTO\Genre\Create\CreateGenreInputDto;
use Core\UseCase\DTO\Genre\Create\CreateGenreOutputDto;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateGenreUseCaseUnitTest extends TestCase
{
    public function test_create_genre()
    {
        $uuid = (string) Uuid::uuid4();

        $useCase = new CreateGenreUseCase(
            $this->mockRepository($uuid, 1),
            $this->mockTransaction(),
            $this->mockCategoryRepository($uuid)
        );

        $response = $useCase->execute($this->mockInput([$uuid]));

        $this->assertInstanceOf(CreateGenreOutputDto::class, $response);
    }

    public function test_create_genre_not_found()
    {
        $this->expectException(NotFoundException::class);

        $uuid = new ValueObjectUuid(Uuid::uuid4());

        $useCase = new CreateGenreUseCase(
            $this->mockRepository($uuid, 0),
            $this->mockTransaction(),
            $this->mockCategoryRepository($uuid)
        );

        $useCase->execute($this->mockInput([$uuid, 'fake', 'fake2']));
    }

    private function mockEntity(string $uuid)
    {
        $mockEntity = Mockery::mock(Genre::class, [
            'Test Genre',
            new ValueObjectUuid($uuid),
        ]);

        $mockEntity->shouldReceive('id')->andReturn(new ValueObjectUuid($uuid));
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        return $mockEntity;
    }

    private function mockRepository(string $uuid, int $times)
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')
            ->times($times)
            ->andReturn($this->mockEntity($uuid));

        return $mockRepository;
    }

    private function mockCategoryRepository(string $uuid)
    {
        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')
            ->once()
            ->andReturn([$uuid]);

        return $mockCategoryRepository;
    }

    private function mockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        return $mockTransaction;
    }

    private function mockInput(array $categoriesId)
    {
        return Mockery::mock(CreateGenreInputDto::class, [
            'Test Genre',
            $categoriesId,
            true,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
