<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\ListCategory\CategoryInputDto;
use Tests\TestCase;
use App\Models\Category as CategoryModel;

class ListCategoryUseCaseTest extends TestCase
{
    public function test_list()
    {
        $categoryDb = CategoryModel::factory()->create();

        $repository = new CategoryEloquentRepository(new CategoryModel());
        $useCase = new ListCategoryUseCase($repository);
        $response = $useCase->execute(new CategoryInputDto($categoryDb->id));

        $this->assertEquals($categoryDb->id, $response->id);
        $this->assertEquals($categoryDb->name, $response->name);
        $this->assertEquals($categoryDb->description, $response->description);
    }
}
