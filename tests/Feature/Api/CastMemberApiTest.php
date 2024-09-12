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
}
