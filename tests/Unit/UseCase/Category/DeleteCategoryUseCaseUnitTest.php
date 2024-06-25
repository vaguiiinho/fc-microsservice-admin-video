<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\ListCategory\CategoryInputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;


class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        $id = (string) Uuid::uuid4()->toString();
        $categoryName = 'name cat';
        $categoryDescription = 'Desc';

        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            $categoryName,
        ]);


        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);

        $this->mockRepo->shouldReceive('findById')
            ->times(1)
            ->andReturn($this->mockEntity);

        $this->mockRepo->shouldReceive('delete')->times(1)->andReturn(true);


        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $id,
        ]);

        $useCase = new UpdateCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDto::class,$responseUseCase);
        $this->assertTrue($responseUseCase->success);
    }
}