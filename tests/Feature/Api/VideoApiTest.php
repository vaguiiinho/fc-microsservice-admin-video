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

    /**
     *  @test 
     */
    public function index()
    {
        $response = $this->getJson($this->endPoint);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     *  @test 
     */
    public function pagination()
    {
        Video::factory()->count(30)->create();

        $response = $this->getJson($this->endPoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'year_launched',
                    'duration',
                    'opened',
                    'rating',
                    'created_at'
                ],
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
    }
}
