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
use Core\Domain\Enum\{
    Rating,
    MediaStatus
};
use Core\Domain\ValueObject\{
    Media,
    Uuid
};
use Core\Domain\Exception\NotFoundException;
use Tests\TestCase;

class VideoEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new VideoEloquentRepository(new Model());
    }
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(VideoEloquentRepository::class, $this->repository);
    }

    public function testInsert()
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

    public function testInsertWithRelationships()
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

        $this->assertEquals($categories->pluck('id')->toArray(), $entityInDb->categoriesId);
        $this->assertEquals($genres->pluck('id')->toArray(), $entityInDb->genresId);
        $this->assertEquals($castMembers->pluck('id')->toArray(), $entityInDb->castMembersId);
    }

    public function testNoFoundVideo()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('fake_id');
    }

    public function testFindById()
    {
        $video = Model::factory()->create();

        $response = $this->repository->findById($video->id);

        $this->assertEquals($video->id, $response->id());
        $this->assertEquals($video->title, $response->title);
    }

    public function testFindAll()
    {
        Model::factory()->count(50)->create();

        $response = $this->repository->findAll();

        $this->assertCount(50, $response);
    }

    public function testFindAllWithFilter()
    {
        Model::factory()->count(10)->create();
        Model::factory()->count(20)->create(['title' => 'Test']);

        $response = $this->repository->findAll(
            filter: 'Test'
        );

        $this->assertCount(20, $response);
        $this->assertDatabaseCount('videos', 30);
    }

    /**
     * @dataProvider dataProviderPagination
     */
    public function testPaginate(
        int $page,
        int $totalPage,
        int $total = 60
    ) {
        Model::factory()->count($total)->create();

        $response = $this->repository->paginate(
            page: $page,
            totalPage: $totalPage,

        );
        $this->assertCount($totalPage, $response->items());
        $this->assertEquals($total, $response->total());
        $this->assertEquals($page, $response->currentPage());
        $this->assertEquals($totalPage, $response->perPage());
    }

    public function dataProviderPagination(): array
    {
        return [
            [
                'page' => 1,
                'totalPage' => 10,
                'total' => 100,
            ],
            [
                'page' => 2,
                'totalPage' => 15,
            ],
            [
                'page' => 5,
                'totalPage' => 5,
                'total' => 65,
            ],
        ];
    }

    public function testUpdateNotFoundId()
    {
        $this->expectException(NotFoundException::class);

        $entity = new Entity(
            title: 'Updated Test Video',
            description: 'Updated Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
        );

        $this->repository->update($entity);
    }

    public function testUpdate()
    {
        $categories = Category::factory()->count(10)->create();
        $genres = Genre::factory()->count(10)->create();
        $castMembers = CastMember::factory()->count(10)->create();

        $video = Model::factory()->create();

        $this->assertDatabaseHas('videos', [
            'title' => $video->title,
        ]);

        $entity = new Entity(
            id: new Uuid($video->id),
            title: 'Updated Test Video',
            description: 'Updated Test Description',
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

        $entityDb = $this->repository->update($entity);

        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'title' => 'Updated Test Video',
            'description' => 'Updated Test Description'
        ]);

        $this->assertDatabaseCount('category_video', 10);
        $this->assertDatabaseCount('genre_video', 10);
        $this->assertDatabaseCount('cast_member_video', 10);

        $this->assertEquals($categories->pluck('id')->toArray(), $entityDb->categoriesId);
        $this->assertEquals($genres->pluck('id')->toArray(), $entityDb->genresId);
        $this->assertEquals($castMembers->pluck('id')->toArray(), $entityDb->castMembersId);
    }

    public function testDeleteNotFoundId()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->delete('fake_id');
    }

    public function testDelete()
    {
        $video = Model::factory()->create();

        $this->repository->delete($video->id);

        $this->assertSoftDeleted('videos', ['id' => $video->id]);
    }

    public function testInsertWithMediaTrailer()
    {
        $entity = new Entity(
            title: 'Updated Test Video',
            description: 'Updated Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
            trailerFile: new Media(
                filePath: 'trailer.mp4',
                mediaStatus: MediaStatus::PROCESSING,
            )
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('medias_video', 0);
        $this->repository->updateMedia($entity);
        $this->repository->updateMedia($entity);
        $this->assertDatabaseCount('medias_video', 1);
        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'trailer.mp4',
            'media_status' => MediaStatus::PROCESSING->value,
        ]);
    }
}
