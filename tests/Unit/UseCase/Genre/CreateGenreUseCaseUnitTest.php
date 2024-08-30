<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\DTO\Genre\Create\{
    CreateGenreInputDto,
    CreateGenreOutputDto
};
use Core\UseCase\Interfaces\TransactionInterface;
use Mockery;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateGenreUseCaseUnitTest extends TestCase
{

    public function test_create_genre()
    {
        $uuid =  new ValueObjectUuid(Uuid::uuid4());

        $mockEntity = Mockery::mock(Genre::class, [
            'Test Genre',
            $uuid,
        ]);

        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);

        $mockRepository->shouldReceive('insert')
            ->andReturn($mockEntity);

        $mockInput = Mockery::mock(CreateGenreInputDto::class, [
            'Test Genre', [$uuid], true
        ]);    

        $useCase = new CreateGenreUseCase($mockRepository, $mockTransaction);

        $response = $useCase->execute($mockInput);

        // $this->assertInstanceOf(CreateGenreOutputDto::class, $response);

        Mockery::close();
    }
}
