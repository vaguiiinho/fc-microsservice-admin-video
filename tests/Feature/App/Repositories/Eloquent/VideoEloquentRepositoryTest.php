<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Video as Model;
use App\Repositories\Eloquent\VideoEloquentRepository;
use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
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

    public function test_insert()
    {
        $entity = new Entity(
            title: 'Test Video',
            description: 'Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
        );

        $this->repository->insert($entity);
        
        $this->assertDatabaseHas('videos', [
            'id' => $entity->id(),
            'title' => 'Test Video', 
            'description' => 'Test Description'
        ]);
    }
}
