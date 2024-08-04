<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteInputDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function test_delete()
    {
        $categoryDb = CategoryModel::factory()->create();

        $repository = new CategoryEloquentRepository(new CategoryModel());
        $useCase = new DeleteCategoryUseCase($repository);
       
        $useCase->execute(
            new CategoryDeleteInputDto(
                id: $categoryDb->id
            )
        );
        $this->assertSoftDeleted($categoryDb);
    }
}
