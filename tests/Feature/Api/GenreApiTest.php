<?php

namespace Tests\Feature\Api;

use App\Models\{
    Category,
    Genre,
};
use Illuminate\Http\Response;
use Tests\TestCase;

class GenreApiTest extends TestCase
{
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
                'from'
            ],
        ]);
        $response->assertJsonCount(15, 'data');
    }

    public function test_store()
    {
        $categories =  Category::factory()->count(10)->create();
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
        $categories =  Category::factory()->count(2)->create();
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
            'errors' => ['name']
        ]);
    }
}
