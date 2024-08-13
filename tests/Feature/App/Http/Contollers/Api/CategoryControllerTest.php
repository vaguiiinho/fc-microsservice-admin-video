<?php

namespace Tests\Feature\App\Http\Contollers\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category as ModelsCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
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
}
