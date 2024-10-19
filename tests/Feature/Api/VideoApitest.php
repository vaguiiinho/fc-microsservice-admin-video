<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class VideoApitest extends TestCase
{
    protected $endPoint = '/api/videos';

    public function testEmpty()
    {
        $response = $this->getJson($this->endPoint);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
