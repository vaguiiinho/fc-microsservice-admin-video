<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\{
    CastMember,
    Category,
    Genre,
    Video as Model,
};
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

    public function test_insert_with_relationships()
    {
        $categories = Category::factory()->count(4)->create();
        $genres = Genre::factory()->count(4)->create();
        $castMembers = CastMember::factory()->count(4)->create();
        
        $entity = new Entity(
            title: 'Test Video',
            description: 'Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
        );

        foreach ($categories as $category) {
            $entity->addCategory($category->id);
        }
        
        foreach ($genres as $genre) {
            $entity->addGenre($genre->id);
        }
        
        foreach ($castMembers as $castMember) {
            $entity->addCastMember($castMember->id);
        }

        $entityInDb = $this->repository->insert($entity);

        $this->assertDatabaseHas('videos', [
            'id' => $entity->id(),
            'title' => 'Test Video', 
            'description' => 'Test Description'
        ]);

        $this->assertDatabaseCount('category_video', 4);
        $this->assertDatabaseCount('genre_video', 4);
        $this->assertDatabaseCount('cast_member_video', 4);

        $this->assertCount(4, $entityInDb->categoriesId);
        $this->assertCount(4, $entityInDb->genresId);
        $this->assertCount(4, $entityInDb->castMembersId);
    }
}
