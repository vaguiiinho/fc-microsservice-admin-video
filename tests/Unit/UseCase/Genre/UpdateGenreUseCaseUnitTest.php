<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    GenreRepositoryInterface,
    CategoryRepositoryInterface
};
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Core\UseCase\DTO\Genre\Update\{
    UpdateGenreInputDto,
    UpdateGenreOutputDto
};
use Core\UseCase\Interfaces\TransactionInterface;
use Mockery;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateGenreUseCaseUnitTest extends TestCase
{
    public function test_update_genre()
    {
        $uuid = (string) Uuid::uuid4();

        $useCase = new UpdateGenreUseCase(
            $this->mockRepository($uuid),
            $this->mockTransaction(),
            $this->mockCategoryRepository($uuid)
        );

        $response = $useCase->execute($this->mockInput([$uuid]));

        $this->assertInstanceOf(UpdateGenreOutputDto::class, $response);
    }

    public function test_update_genre_not_found()
    {
        $this->expectException(NotFoundException::class);

        $uuid = new ValueObjectUuid(Uuid::uuid4());

        $useCase = new UpdateGenreUseCase(
            $this->mockRepository($uuid),
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

    private function mockRepository(string $uuid)
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')
            ->andReturn($this->mockEntity($uuid));

        return $mockRepository;
    }

    private function mockCategoryRepository(string $uuid)
    {
        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')->andReturn([$uuid]);

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
        return Mockery::mock(UpdateGenreInputDto::class, [
            'Test Genre',
            $categoriesId,
            true
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
