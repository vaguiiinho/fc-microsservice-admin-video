<?php

namespace Tests\Feature\Api;

use Illuminate\Http\Response;
use Tests\TestCase;

class GenreApiTest extends TestCase
{
    protected $endpoint = '/api/genres';
    public function test_list_empty_genres()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonCount(0, 'data');
    }
}
