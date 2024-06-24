<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDto;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;


class UpdateCategoryUseCaseUnitTest extends TestCase
{
    public function testRenameCategory()
    {
        $id = (string) Uuid::uuid4()->toString();
        $categoryName = 'name cat';
        $categoryDescription = 'Desc';

        $this->mockEntity = Mockery::mock(Category::class, [
            $id, $categoryName , $categoryDescription
        ]);

        $this->mockEntityUpdate = Mockery::mock(Category::class, [
            $id, 'new name', 'new desc'
        ]);


        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntityUpdate->shouldReceive('id')->andReturn($id);

        $this->mockEntity->shouldReceive('update');

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);

        $this->mockRepo->shouldReceive('findById')
            ->times(1)
            ->andReturn($this->mockEntity);

        $this->mockRepo->shouldReceive('update')
            ->times(1)
            ->andReturn($this->mockEntityUpdate);



        $this->mockInputDto = Mockery::mock(CategoryUpdateInputDto::class, [
            $id,
            'new name'
        ]);

        $useCase = new UpdateCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryUpdateOutputDto::class, $responseUseCase);
        $this->assertEquals('new name', $responseUseCase->name);
        $this->assertEquals('new desc', $responseUseCase->description);
    }
}