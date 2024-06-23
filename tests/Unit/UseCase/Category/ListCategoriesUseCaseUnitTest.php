<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\ListCategory\CategoryInputDto;
use Core\UseCase\DTO\Category\ListCategory\CategoryOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;


class ListCategoriesUseCaseUnitTest extends TestCase
{
    public function testListCategoriesEmpty()
    {
        $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')
        ->times(1)
        ->andReturn([]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('paginate')
            ->times(1)
            ->andReturn($this->mockPagination);

        $this->mockInputDto = Mockery::mock(ListCategoriesInputDto::class, ['id', 'filter']);

        $useCase = new ListCategoriesUseCase($this->mockRepo);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertEquals(0, count($response->items));
        $this->assertInstanceOf(ListCategoriesOutputDto::class, $response);

        Mockery::close();
    }
}