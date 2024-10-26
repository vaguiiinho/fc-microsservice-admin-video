<?php

namespace Tests\Feature\Api;

use App\Models\Video;
use Core\Domain\Enum\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class VideoApiTest extends TestCase
{
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
        $data = [
            'title' => 'Test Video',
            'description' => 'Test Description',
            'year_launched' => 2022,
            'duration' => 1,
            'opened' => true,
            'rating' => 'L',
            'categories' => [],
            'genres' => [],
            'cast_members' => [],
        ];

        $response = $this->postJson($this->endPoint, $data);

        $response->assertCreated();
        $this->assertDatabaseCount('videos', 1);
    }
}
