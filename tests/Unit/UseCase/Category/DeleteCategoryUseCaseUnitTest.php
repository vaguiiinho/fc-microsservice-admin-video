<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteInputDto;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;


class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        $id = (string) Uuid::uuid4()->toString();

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);

        $this->mockRepo->shouldReceive('delete')->times(1)->andReturn(true);


        $this->mockInputDto = Mockery::mock(CategoryDeleteInputDto::class, [$id]);

        $useCase = new DeleteCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDto::class,$responseUseCase);
        $this->assertTrue($responseUseCase->success);
    }
}