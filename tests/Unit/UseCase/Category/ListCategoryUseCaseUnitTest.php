<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;


class CreateCategoryUseCaseUnitTest extends TestCase
{

    public function testGetById()
    {
        $id = (string) Uuid::uuid4()->toString();

        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            'name cat'
        ]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')
            ->with($id)
            ->times(1)
            ->andReturn($this->mockEntity);


        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $id
        ]);

        $useCase = new ListCategoryUseCase($this->mockRepo);

        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryOutputDto::class, $response);
        $this->assertEquals($id, $response->id);
        $this->assertEquals('name cat', $response->name);

        Mockery::close();
    }
}