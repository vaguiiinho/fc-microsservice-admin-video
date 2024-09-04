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
            'data' => [
                '*' => ['id', 'name', 'is_active'],
            ],
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
    }
}
