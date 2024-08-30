<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\DTO\Genre\List\{
    ListGenresInputDto,
    ListGenresOutputDto
};
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\ListGenresUseCase;
use Mockery;
use stdClass;

class ListGenresUseCaseUnitTest extends TestCase
{

    public function test_list_genres()
    {
        $mockPagination = $this->mockPagination();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mockRepository->shouldReceive('paginate')
        ->times(1)
        ->andReturn($mockPagination);

        $mockDtoInput = Mockery::mock(ListGenresInputDto::class, [
            'test', "desc", 1, 15
        ]);


        $useCase = new ListGenresUseCase($mockRepository);

        $response = $useCase->execute($mockDtoInput);

        $this->assertInstanceOf(ListGenresOutputDto::class, $response);

        Mockery::close();
    }

    protected function mockPagination(array $items = [])
    {
        $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')->andReturn($items);
        $this->mockPagination->shouldReceive('total')->andReturn(1);
        $this->mockPagination->shouldReceive('firstPage')->andReturn(1);
        $this->mockPagination->shouldReceive('lastPage')->andReturn(1);
        $this->mockPagination->shouldReceive('currentPage')->andReturn(1);
        $this->mockPagination->shouldReceive('perPage')->andReturn(1);
        $this->mockPagination->shouldReceive('to')->andReturn(1);
        $this->mockPagination->shouldReceive('from')->andReturn(1);
        return $this->mockPagination;
    }
}
