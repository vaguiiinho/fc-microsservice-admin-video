<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\DTO\Genre\Delete\{
    DeleteGenreInputDto,
    DeleteGenreOutputDto
};
use Core\UseCase\Genre\DeleteGenreUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class DeleteGenreUseCaseUnitTest extends TestCase
{

    public function test_genre_delete()
    {
        $id = (string) Uuid::uuid4()->toString();

       $mockRepo = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

       $mockRepo->shouldReceive('delete')->times(1)->andReturn(true);


       $mockInputDto = Mockery::mock(DeleteGenreInputDto::class, [$id]);

        $useCase = new DeleteGenreUseCase($mockRepo);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(DeleteGenreOutputDto::class, $responseUseCase);
        $this->assertTrue($responseUseCase->success);
    }

    public function test_delete_false()
    {
        $id = (string) Uuid::uuid4()->toString();

        $mockRepo = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mockRepo->shouldReceive('delete')->times(1)->andReturn(false);


        $mockInputDto = Mockery::mock(DeleteGenreInputDto::class, [$id]);

        $useCase = new DeleteGenreUseCase($mockRepo);
        $responseUseCase = $useCase->execute($mockInputDto);

        $this->assertFalse($responseUseCase->success);
    }

    protected function teardown(): void
    {
        Mockery::close();
        parent::teardown();
    }}
