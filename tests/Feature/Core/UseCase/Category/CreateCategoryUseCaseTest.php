<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDto;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    
    public function test_create()
    {
        $repository = new CategoryEloquentRepository(new CategoryModel());
        $useCase = new CreateCategoryUseCase($repository);
        $response = $useCase->execute(
            new CategoryCreateInputDto(
                name: 'Test',
                description: 'Test description',
                isActive: true,
            )
        );
        $this->assertEquals('Test', $response->name);
        $this->assertNotEmpty($response->id);
        $this->assertDatabaseHas('categories', [
            'id' => $response->id,
            'name' => 'Test',
            'description' => 'Test description',
            'is_active' => true,
        ]);
    }
}
