<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Enums\ImageTypes;
use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video as Model;
use App\Repositories\Eloquent\VideoEloquentRepository;
use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;

class VideoEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new VideoEloquentRepository(new Model);
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
            'description' => 'Test Description',
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
            'description' => 'Test Description',
        ]);

        $this->assertDatabaseCount('category_video', 4);
        $this->assertDatabaseCount('genre_video', 4);
        $this->assertDatabaseCount('cast_member_video', 4);

        $this->assertEquals($categories->pluck('id')->toArray(), $entityInDb->categoriesId);
        $this->assertEquals($genres->pluck('id')->toArray(), $entityInDb->genresId);
        $this->assertEquals($castMembers->pluck('id')->toArray(), $entityInDb->castMembersId);
    }

    public function test_no_found_video()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('fake_id');
    }

    public function test_find_by_id()
    {
        $video = Model::factory()->create();

        $response = $this->repository->findById($video->id);

        $this->assertEquals($video->id, $response->id());
        $this->assertEquals($video->title, $response->title);
    }

    public function test_find_all()
    {
        Model::factory()->count(50)->create();

        $response = $this->repository->findAll();

        $this->assertCount(50, $response);
    }

    public function test_find_all_with_filter()
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
    public function test_paginate(
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

    public function test_update_not_found_id()
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

    public function test_update()
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
            'description' => 'Updated Test Description',
        ]);

        $this->assertDatabaseCount('category_video', 10);
        $this->assertDatabaseCount('genre_video', 10);
        $this->assertDatabaseCount('cast_member_video', 10);

        $this->assertEquals($categories->pluck('id')->toArray(), $entityDb->categoriesId);
        $this->assertEquals($genres->pluck('id')->toArray(), $entityDb->genresId);
        $this->assertEquals($castMembers->pluck('id')->toArray(), $entityDb->castMembersId);
    }

    public function test_delete_not_found_id()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->delete('fake_id');
    }

    public function test_delete()
    {
        $video = Model::factory()->create();

        $this->repository->delete($video->id);

        $this->assertSoftDeleted('videos', ['id' => $video->id]);
    }

    public function test_insert_with_media_trailer()
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
        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'trailer.mp4',
            'media_status' => MediaStatus::PROCESSING->value,
        ]);

        $entity->setTrailerFile(
            new Media(
                filePath: 'trailer_updated.mp4',
                mediaStatus: MediaStatus::COMPLETED,
                encodedPath: 'test2enconde.mp4'
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('medias_video', 1);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'trailer_updated.mp4',
            'media_status' => MediaStatus::COMPLETED->value,
            'encoded_path' => 'test2enconde.mp4',
        ]);

        $this->assertNotNull($entityDb->trailerFile());
    }

    public function test_insert_with_image_banner()
    {
        $entity = new Entity(
            title: 'Updated Test Video',
            description: 'Updated Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
            bannerFile: new Image(
                path: 'banner.jpg',
            )
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);
        $this->repository->updateMedia($entity);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'banner.jpg',
            'type' => ImageTypes::BANNER->value,
        ]);

        $entity->setBannerFile(
            new Image(
                path: 'banner_updated.jpg',
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'banner_updated.jpg',
            'type' => ImageTypes::BANNER->value,
        ]);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertNotNull($entityDb->bannerFile());
    }

    public function test_insert_with_media_video()
    {
        $entity = new Entity(
            title: 'Updated Test Video',
            description: 'Updated Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
            videoFile: new Media(
                filePath: 'trailer.mp4',
                mediaStatus: MediaStatus::PROCESSING,
            )
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('medias_video', 0);
        $this->repository->updateMedia($entity);
        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'trailer.mp4',
            'media_status' => MediaStatus::PROCESSING->value,
        ]);

        $entity->setVideoFile(
            new Media(
                filePath: 'trailer_updated.mp4',
                mediaStatus: MediaStatus::COMPLETED,
                encodedPath: 'test2enconde.mp4'
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('medias_video', 1);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'trailer_updated.mp4',
            'media_status' => MediaStatus::COMPLETED->value,
            'encoded_path' => 'test2enconde.mp4',
        ]);

        $this->assertNotNull($entityDb->videoFile());
    }

    public function test_insert_with_image_thumb()
    {
        $entity = new Entity(
            title: 'Updated Test Video',
            description: 'Updated Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
            thumbFile : new Image(
                path: 'thumb.jpg',
            )
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);
        $this->repository->updateMedia($entity);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'thumb.jpg',
            'type' => ImageTypes::THUMB->value,
        ]);

        $entity->setThumbFile(
            new Image(
                path: 'thumb_updated.jpg',
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'thumb_updated.jpg',
            'type' => ImageTypes::THUMB->value,
        ]);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertNotNull($entityDb->thumbFile());
    }

    public function test_insert_with_image_thumb_half()
    {
        $entity = new Entity(
            title: 'Updated Test Video',
            description: 'Updated Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
            thumbHalf  : new Image(
                path: 'thumb.jpg',
            )
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);
        $this->repository->updateMedia($entity);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'thumb.jpg',
            'type' => ImageTypes::THUMB_HALF->value,
        ]);

        $entity->setThumbHalf(
            new Image(
                path: 'thumb_half_updated.jpg',
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'thumb_half_updated.jpg',
            'type' => ImageTypes::THUMB_HALF->value,
        ]);

        $this->assertDatabaseCount('images_video', 1);

        $this->assertNotNull($entityDb->thumbHalf());
    }
}
