<?php

namespace Tests\Feature\Api;

use App\Models\CastMember;
use Illuminate\Http\Response;
use Tests\TestCase;

class CastMemberApiTest extends TestCase
{
    private $endPoint = '/api/cast-members';

    public function test_get_all_empty()
    {
        $response = $this->getJson($this->endPoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function test_get_pagination()
    {
        CastMember::factory()->count(30)->create();

        $response = $this->getJson($this->endPoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from'
            ],
        ]);
    }

    public function test_get_pagination_two()
    {
        CastMember::factory()->count(20)->create();

        $response = $this->getJson("$this->endPoint/?page=2");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        $this->assertEquals(20, $response['meta']['total']);
        $this->assertEquals(2, $response['meta']['current_page']);
    }

    public function test_pagination_with_filter()
    {
        CastMember::factory()->count(20)->create();
        CastMember::factory()->count(5)->create(['name' => 'teste']);

        $response = $this->getJson("$this->endPoint/?filter=teste");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
    }

    public function test_show_not_found()
    {
        $response = $this->getJson("$this->endPoint/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(['message' => 'Cast member fake_id not found']);
    }

    public function test_show()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->getJson("$this->endPoint/$castMember->id");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ]
        ]);
    }

    public function test_store_validation()
    {
        $response = $this->postJson($this->endPoint, []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => ['name', 'type'],
        ]);
    }

    public function test_store()
    {
        $response = $this->postJson($this->endPoint, [
            'name' => 'Teste',
            'type' => 1,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ],
        ]);

        $this->assertDatabaseHas('cast_members', [
            'name' => 'Teste',
            'type' => 1,
        ]);
    }

    public function test_update_not_found()
    {
        $response = $this->putJson("$this->endPoint/fake_id", [
            'name' => 'Teste',
            'type' => 1,
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_validation()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->putJson("$this->endPoint/$castMember->id", []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
           'message',
            'errors' => ['name'],
        ]);
    }

    public function test_update()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->putJson("$this->endPoint/$castMember->id", [
            'name' => 'Teste',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
            ],
        ]);

        $this->assertDatabaseHas('cast_members', [
            'id' => $castMember->id,
            'name' => 'Teste',
        ]);
    }

    public function test_delete_not_found()
    {
        $response = $this->deleteJson("$this->endPoint/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete()
    {
        $castMember = CastMember::factory()->create();

        $response = $this->deleteJson("$this->endPoint/$castMember->id");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('cast_members', [
            'id' => $castMember->id,
        ]);
    }
}
