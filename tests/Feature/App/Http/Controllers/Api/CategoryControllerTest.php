<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category as ModelsCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    protected $repository;

    protected $controller;

    protected function setUp(): void
    {
        $this->repository = new CategoryEloquentRepository(new ModelsCategory);
        $this->controller = new CategoryController;
        parent::setUp();
    }

    public function test_index()
    {

        $useCase = new ListCategoriesUseCase($this->repository);

        $response = $this->controller->index(new Request, $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_store()
    {
        $useCase = new CreateCategoryUseCase($this->repository);
        $request = new StoreCategoryRequest;
        $request->headers->set('Content-Type', 'application/json');

        $request->setJson(new ParameterBag([
            'name' => 'Test',
        ]));

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function test_show()
    {
        $category = ModelsCategory::factory()->create();

        $response = $this->controller->show(
            useCase: new ListCategoryUseCase($this->repository),
            id: $category->id,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function test_update()
    {
        $category = ModelsCategory::factory()->create();

        $request = new UpdateCategoryRequest;
        $request->headers->set('Content-Type', 'application/json');

        $request->setJson(new ParameterBag([
            'name' => 'Updated',
        ]));

        $response = $this->controller->update(
            request: $request,
            useCase: new UpdateCategoryUseCase($this->repository),
            id: $category->id
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function test_delete()
    {
        $category = ModelsCategory::factory()->create();

        $response = $this->controller->destroy(
            useCase: new DeleteCategoryUseCase($this->repository),
            id: $category->id
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->status());
    }
}
