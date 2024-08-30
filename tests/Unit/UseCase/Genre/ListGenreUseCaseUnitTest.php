<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\DTO\Genre\List\{
    ListGenreInputDto,
    ListGenreOutputDto
};
use Core\UseCase\Genre\ListGenreUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ListGenreUseCaseUnitTest extends TestCase
{

    public function test_list_genre()
    {
        $uuid =  new ValueObjectUuid(Uuid::uuid4());

        $mockEntity = Mockery::mock(Genre::class, [
            'Test Genre',
            $uuid,
        ]);

        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(GenreRepositoryInterface::class);

        $mockRepository->shouldReceive('findById')
            ->times(1)
            ->andReturn($mockEntity);

        $mockInput = Mockery::mock(ListGenreInputDto::class, [$uuid]);

        $useCase = new ListGenreUseCase($mockRepository);

        $response = $useCase->execute($mockInput);
        $this->assertInstanceOf(ListGenreOutputDto::class, $response);
    }
}
