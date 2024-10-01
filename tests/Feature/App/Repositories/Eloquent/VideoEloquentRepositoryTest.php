<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Video as Model;
use App\Repositories\Eloquent\VideoEloquentRepository;
use Tests\TestCase;

class VideoEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new VideoEloquentRepository(new Model());
    }
    public function test_implements_interface()
    {
        $this->assertInstanceOf(VideoEloquentRepository::class, $this->repository);
    }
}
