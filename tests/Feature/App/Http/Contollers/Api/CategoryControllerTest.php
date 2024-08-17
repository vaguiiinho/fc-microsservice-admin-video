<?php

namespace Tests\Feature\App\Http\Contollers\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Requests\{
    StoreCategoryRequest,
    UpdateCategoryRequest
};
use App\Models\Category as ModelsCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\{
    CreateCategoryUseCase,
    ListCategoriesUseCase,
    ListCategoryUseCase,
    UpdateCategoryUseCase
};
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
        $this->controller = new CategoryController();
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
        $request = new StoreCategoryRequest();
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

        $request = new UpdateCategoryRequest();
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
}
