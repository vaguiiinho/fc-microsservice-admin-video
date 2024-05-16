<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use UseCase\Category\CreateCategoryUseCase;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    public function testNewCategory()
    {
        $categoryId = '1';
        $categoryName = 'name cat';

        $this->mockEntity = Mockery::mock(Category::class, [$categoryId, $categoryName]);
        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('insert')->andReturn($this->mockEntity);

       $useCasse = new CreateCategoryUseCase($this->mockRepo);
       $useCasse->execute();
    }
}
