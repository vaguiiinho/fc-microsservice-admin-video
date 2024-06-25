<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategories\{
    ListCategoriesInputDto,
    ListCategoriesOutputDto
};
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;


class ListCategoriesUseCaseUnitTest extends TestCase
{
    public function testListCategoriesEmpty()
    {
        $mockPagination = $this->mockPagination();

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('paginate')
            ->times(1)
            ->andReturn($mockPagination);

        $this->mockInputDto = Mockery::mock(ListCategoriesInputDto::class, ['filter', 'desc']);

        $useCase = new ListCategoriesUseCase($this->mockRepo);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $response);
        $this->assertCount(0, $response->items);
        $this->assertEquals(1, $response->total);
        $this->assertEquals(1, $response->first_page);
        $this->assertEquals(1, $response->last_page);
        $this->assertEquals(1, $response->current_page);
        $this->assertEquals(1, $response->per_page);
        $this->assertEquals(1, $response->to);
        $this->assertEquals(1, $response->from);
    }

    public function testListCategories()
    {
        $register = new stdClass();
        $register->id = '123';
        $register->name = 'New cat';
        $register->description = 'New cat description';
        $register->is_active = true;
        $register->created_at = '2023-01-01 12:12:12';
        $register->updated_at = '2023-01-01 12:12:12';
        $register->deleted_at = '2023-01-01 12:12:12';

        $mockPagination = $this->mockPagination([
            $register,
        ]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('paginate')
            ->times(1)
            ->andReturn($mockPagination);

        $this->mockInputDto = Mockery::mock(ListCategoriesInputDto::class, ['filter', 'desc']);

        $useCase = new ListCategoriesUseCase($this->mockRepo);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $response);
        $this->assertCount(1, $response->items);
       $this->assertInstanceOf(stdClass::class, $response->items[0]);
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
    protected function teardown(): void
    {
        Mockery::close();
        parent::teardown();
    }
}