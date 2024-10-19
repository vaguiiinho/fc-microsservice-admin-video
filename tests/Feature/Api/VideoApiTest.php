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

        // $response->assertStatus(Response::HTTP_OK);
        $response->assertOk();
    }

    /**
     *  @test 
     */
    public function pagination()
    {
        Video::factory()->count(30)->create();

        $response = $this->getJson($this->endPoint);
        // $response->assertStatus(Response::HTTP_OK);
        $response->assertOk();
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('meta.per_page', 15);
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
