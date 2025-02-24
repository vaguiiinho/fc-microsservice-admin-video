<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\WithoutMiddlewareTrait;

class GenreApiTest extends TestCase
{
    use WithoutMiddlewareTrait;

    protected $endpoint = '/api/genres';

    public function test_list_empty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonCount(0, 'data');
    }

    public function test_list_all()
    {
        Genre::factory()->count(30)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ],
        ]);
        $response->assertJsonCount(15, 'data');
    }

    public function test_store()
    {
        $categories = Category::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        $response = $this->postJson($this->endpoint, [
            'name' => 'New Category',
            'is_active' => false,
            'categories_id' => $categoriesId,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
            ],
        ]);
    }

    public function test_validation_store()
    {
        $categories = Category::factory()->count(2)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        $data = [
            'name' => '',
            'is_active' => false,
            'categories_id' => $categoriesId,
        ];

        $response = $this->postJson($this->endpoint, $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => ['name'],
        ]);
    }

    public function test_list_genre_not_found()
    {
        $response = $this->getJson("$this->endpoint/fake_value");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(['message' => 'Genre fake_value not found']);
    }

    public function test_list_genre()
    {
        $genre = Genre::factory()->create();
        $response = $this->getJson("$this->endpoint/$genre->id");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
            ],
        ]);

        $this->assertEquals($genre->id, $response['data']['id']);
    }

    public function test_update_genre_not_found()
    {
        $categories = Category::factory()->count(2)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        $response = $this->putJson("$this->endpoint/fake_value", [
            'name' => 'Updated Genre',
            'categories_id' => $categoriesId,
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(['message' => 'Genre fake_value not found']);
    }

    public function test_update_genre()
    {
        $genre = Genre::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        $response = $this->putJson("$this->endpoint/$genre->id", [
            'name' => 'Updated Genre',
            'categories_id' => $categoriesId,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
            ],
        ]);

        $this->assertEquals('Updated Genre', $response['data']['name']);

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => 'Updated Genre',
            'is_active' => true,
        ]);
    }

    public function test_validation_update()
    {
        $response = $this->putJson("$this->endpoint/fake_id", [
            'name' => 'updated name',
            'categories_id' => [],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['categories_id'],
        ]);
    }

    public function test_delete_genre_not_found()
    {
        $response = $this->deleteJson("$this->endpoint/fake_value");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(['message' => 'Genre fake_value not found']);
    }

    public function test_delete_genre()
    {
        $genre = Genre::factory()->create();

        $response = $this->deleteJson("$this->endpoint/$genre->id");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
