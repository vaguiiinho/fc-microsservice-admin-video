<?php

namespace Tests\Feature\Core\UseCase\Video\Create;

use App\Models\{
    CastMember,
    Category,
    Genre,
    Video as Model,
};
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface,
    CategoryRepositoryInterface
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateVideoUseCaseTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function test_create(
        int $categories,
        int $genres,
        int $castMembers
    ) {
        $useCase = new CreateVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            $this->app->make(FileStorageInterface::class),
            $this->app->make(VideoEventManagerInterface::class),
            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class)
        );

        $categoriesIds = Category::factory()->count($categories)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count($genres)->create()->pluck('id')->toArray();
        $castMemberIds = CastMember::factory()->count($castMembers)->create()->pluck('id')->toArray();

        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video.mp4');

        $file = [
            'tmp_name' => $fakeFile->getPathName(),
            'name' => $fakeFile->getFileName(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $input = new CreateInputVideoDTO(
            title: 'Test Video',
            description: 'Test Description',
            yearLaunched: 2022,
            duration: 120,
            opened: true,
            rating: Rating::L,
            categories: $categoriesIds,
            genres: $genresIds,
            castMembers: $castMemberIds,
            videoFile: $file,
        );

        $response = $useCase->exec(input: $input);

        $this->assertEquals($input->title, $response->title);
        $this->assertEquals($input->description, $response->description);
        $this->assertEquals($input->yearLaunched, $response->yearLaunched);
        $this->assertEquals($input->duration, $response->duration);
        $this->assertEquals($input->opened, $response->opened);
        $this->assertEquals($input->rating, $response->rating);

        $this->assertCount($categories, $response->categories);
        $this->assertEqualsCanonicalizing($input->categories, $response->categories);
        $this->assertCount($genres, $response->genres);
        $this->assertEqualsCanonicalizing($input->genres, $response->genres);
        $this->assertCount($castMembers, $response->castMembers);
        $this->assertEqualsCanonicalizing($input->castMembers, $response->castMembers);

        $this->assertNotNull($response->videoFile);
        $this->assertNull($response->trailerFile);
        $this->assertNull($response->thumbFile);
        $this->assertNull($response->thumbHalf);
        $this->assertNull($response->bannerFile);
    }

    protected function provider(): array
    {
        return [
            'Test With all Ids' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 3,
            ],
            'Test With categories and genres' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 0,
            ]
        ];
    }
}
