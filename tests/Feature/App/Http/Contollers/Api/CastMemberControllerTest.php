<?php

namespace Tests\Feature\App\Http\Contollers\Api;

use App\Http\Controllers\Api\CastMemberController;
use App\Http\Requests\{
    StoreCastMemberRequest,
    UpdateCastMemberRequest
};
use App\Models\CastMember as ModelsCastMember;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\UseCase\CastMember\{
    CreateCastMemberUseCase,
    ListCastMembersUseCase,
    ListCastMemberUseCase,
    UpdateCastMemberUseCase,
    DeleteCastMemberUseCase
};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CastMemberControllerTest extends TestCase
{
    protected $repository;
    protected $controller;
    protected function setUp(): void
    {
        $this->repository = new CastMemberEloquentRepository(new ModelsCastMember);
        $this->controller = new CastMemberController();
        parent::setUp();
    }

    public function test_index()
    {

        $useCase = new ListCastMembersUseCase($this->repository);

        $response = $this->controller->index(new Request, $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_store()
    {
        $useCase = new CreateCastMemberUseCase($this->repository);
        $request = new StoreCastMemberRequest();
        $request->headers->set('Content-Type', 'application/json');

        $request->setJson(new ParameterBag([
            'name' => 'Test',
            'type' => 1
        ]));

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function test_show()
    {
        $castMember = ModelsCastMember::factory()->create();

        $response = $this->controller->show(
            useCase: new ListCastMemberUseCase($this->repository),
            id: $castMember->id,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function test_update()
    {
        $castMember = ModelsCastMember::factory()->create();

        $request = new UpdateCastMemberRequest();
        $request->headers->set('Content-Type', 'application/json');

        $request->setJson(new ParameterBag([
            'name' => 'Updated',
            'type' => 1
        ]));

        $response = $this->controller->update(
            request: $request,
            useCase: new UpdateCastMemberUseCase($this->repository),
            id: $castMember->id
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function test_delete()
    {
        $castMember = ModelsCastMember::factory()->create();

        $response = $this->controller->destroy(
            useCase: new DeleteCastMemberUseCase($this->repository),
            id: $castMember->id
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->status());
    }
}
