<?php

namespace Tests\Feature\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class VideoApiTest extends TestCase
{
    protected $endPoint = '/api/videos';

    public function testEmpty()
    {
        $response = $this->getJson($this->endPoint);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testPagination()
    {
        Video::factory()->count(30)->create();

        $response = $this->getJson($this->endPoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
    }
}
