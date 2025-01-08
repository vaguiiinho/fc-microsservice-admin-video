<?php

namespace Tests\Feature\Api;

use App\Models\{
    CastMember,
    Category,
    Genre,
    Video
};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\WithoutMiddlewareTrait;

class VideoApiTest extends TestCase
{
    use WithoutMiddlewareTrait;

    protected $endPoint = '/api/videos';
    protected $serializedFields = [
        'id',
        'title',
        'description',
        'year_launched',
        'duration',
        'opened',
        'rating',
        'created_at'
    ];

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
     * @dataProvider dataProviderPagination
     */
    public function pagination(
        int $total,
        int $currentPage,
        int $page = 1,
        int $perPage = 15,
        string $filter = ''

    ) {
        Video::factory()->count($total)->create();

        if ($filter) {

            Video::factory()->count($total)->create(['title' => $filter]);
        }

        $params = http_build_query([
            'page' => $page,
            'totalPage' => $perPage,
            'order' => 'DESC',
            'filter' => $filter,
        ]);

        $response = $this->getJson("$this->endPoint?$params");
        // $response->assertStatus(Response::HTTP_OK);
        $response->assertOk();
        $response->assertJsonCount($currentPage, 'data');
        $response->assertJsonPath('meta.current_page', $page);
        $response->assertJsonPath('meta.per_page', $perPage);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->serializedFields
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

    protected function dataProviderPagination()
    {
        return [
            [
                'total' => 20,
                'currentPage' => 15,
                'page' => 1,
                'perPage' => 15,
            ],
            [
                'total' => 10,
                'currentPage' => 10,
                'page' => 1,
                'perPage' => 15,
            ],
            [
                'total' => 0,
                'currentPage' => 0,
                'page' => 1,
                'perPage' => 15,
            ],
            [
                'total' => 20,
                'currentPage' => 5,
                'page' => 2,
                'perPage' => 15,
            ],
            [
                'total' => 40,
                'currentPage' => 10,
                'page' => 4,
                'perPage' => 10,
            ],
            [
                'total' => 10,
                'currentPage' => 10,
                'page' => 1,
                'perPage' => 10,
                'filter' => 'test',
            ],

        ];
    }

    /**
     *  @test 
     */
    public function show()
    {
        $video = Video::factory()->create();

        $response = $this->getJson("$this->endPoint/{$video->id}");

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => $this->serializedFields
        ]);
    }

    /**
     *  @test 
     */
    public function show_not_found()
    {
        $response = $this->getJson("$this->endPoint/fake_id");

        $response->assertNotFound();
        $response->assertJson(['message' => 'Video not found']);
    }

    /**
     *  @test 
     */
    public function store()
    {
        $mediaVideoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $imageVideoFile = UploadedFile::fake()->image('image.png');

        $categoriesIds = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count(3)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count(3)->create()->pluck('id')->toArray();

        $data = [
            'title' => 'Test Video',
            'description' => 'Test Description',
            'year_launched' => 2022,
            'duration' => 1,
            'opened' => true,
            'rating' => 'L',
            'categories' => $categoriesIds,
            'genres' => $genresIds,
            'cast_members' => $castMembersIds,
            //'video_file' => $mediaVideoFile,
            'trailer_file' => $mediaVideoFile,
            'banner_file' => $imageVideoFile,
            'thumb_file' => $imageVideoFile,
            'thumb_half_file' => $imageVideoFile,
        ];

        $response = $this->postJson($this->endPoint, $data);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => $this->serializedFields
        ]);
        //$this->assertDatabaseCount('videos', 1);
        $this->assertDatabaseHas('videos', [
            'id' => $response->json('data.id')
        ]);

        $this->assertEquals($categoriesIds, $response->json('data.categories'));
        $this->assertEquals($genresIds, $response->json('data.genres'));
        $this->assertEquals($castMembersIds, $response->json('data.cast_members'));

        //Storage::assertExists($response->json('data.video'));
        Storage::assertExists($response->json('data.trailer'));
        Storage::assertExists($response->json('data.banner'));
        Storage::assertExists($response->json('data.thumb'));
        Storage::assertExists($response->json('data.thumb_half'));

        Storage::deleteDirectory($response->json('data.id'));
    }


    /**
     * @test
     */
    public function update()
    {
        $video = Video::factory()->create();

        $mediaVideoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $imageVideoFile = UploadedFile::fake()->image('image.png');

        $categoriesIds = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count(3)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count(3)->create()->pluck('id')->toArray();

        $data = [
            'title' => 'title updated',
            'description' => 'desc updated',
            'categories' => $categoriesIds,
            'genres' => $genresIds,
            'cast_members' => $castMembersIds,
            // 'video_file' => $mediaVideoFile,
            'trailer_file' => $mediaVideoFile,
            'banner_file' => $imageVideoFile,
            'thumb_file' => $imageVideoFile,
            'thumb_half_file' => $imageVideoFile,
        ];
        $response = $this->putJson("$this->endPoint/{$video->id}", $data);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => $this->serializedFields,
        ]);

        $this->assertDatabaseCount('videos', 1);
        $this->assertDatabaseHas('videos', [
            'id' => $response->json('data.id'),
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        $this->assertEquals($categoriesIds, $response->json('data.categories'));
        $this->assertEquals($genresIds, $response->json('data.genres'));
        $this->assertEquals($castMembersIds, $response->json('data.cast_members'));

        // Storage::assertExists($response->json('data.video'));
        Storage::assertExists($response->json('data.trailer'));
        Storage::assertExists($response->json('data.banner'));
        Storage::assertExists($response->json('data.thumb'));
        Storage::assertExists($response->json('data.thumb_half'));

        Storage::deleteDirectory($response->json('data.id'));
    }

    /**
     * @test
     */
    // #[Test]
    public function storeValidation()
    {
        $response = $this->postJson($this->endPoint, []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'title',
            'description',
            'year_launched',
            'duration',
            'rating',
            'opened',
            'categories',
            'genres',
            'cast_members',
        ]);
    }

    /**
     * @test
     */
    public function destroy()
    {
        $video = Video::factory()->create();

        $response = $this->deleteJson("$this->endPoint/{$video->id}");
        $response->assertNoContent();

        $this->assertSoftDeleted('videos', [
            'id' => $video->id,
        ]);
    }

    /**
     * @test
     */
    public function destroyNotFound()
    {
        $response = $this->deleteJson("$this->endPoint/fake_id");
        $response->assertNotFound();
    }
}
