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
use Tests\Stubs\UploadFileStub;
use Tests\Stubs\VideoEventStub;
use Tests\TestCase;

abstract class BaseVideoUseCase extends TestCase
{
    abstract function useCase(): string;

    abstract function inputDto(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMemberIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
    ): object;
    /**
     * @dataProvider provider
     */
    public function test_create(
        int $categories,
        int $genres,
        int $castMembers,
        bool $withMediaVideo = false,
        bool $withMediaTrailer = false,
        bool $withMediaThumb = false,
        bool $withMediaHalf = false,
        bool $withMediaBanner = false
    ) {
        $useCase = new ($this->useCase())(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            // $this->app->make(FileStorageInterface::class),
            new UploadFileStub(),
            // $this->app->make(VideoEventManagerInterface::class),
            new VideoEventStub(),
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
        
        );

        $input = $this->inputDto(
            categoriesIds: $categoriesIds,
            genresIds: $genresIds,
            castMemberIds: $castMemberIds,
            videoFile: $withMediaVideo? $file : null,
            trailerFile: $withMediaTrailer? $file : null,
            thumbFile: $withMediaThumb? $file : null,
            thumbHalf: $withMediaHalf? $file : null,
            bannerFile: $withMediaBanner? $file : null,
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

        $this->assertTrue($withMediaVideo ? $response->videoFile !== null : $response->videoFile === null);
        $this->assertTrue($withMediaTrailer ? $response->trailerFile !== null : $response->trailerFile === null);
        $this->assertTrue($withMediaThumb ? $response->thumbFile !== null : $response->thumbFile === null);
        $this->assertTrue($withMediaHalf ? $response->thumbHalf !== null : $response->thumbHalf === null);
        $this->assertTrue($withMediaBanner ? $response->bannerFile !== null : $response->bannerFile === null);
    }

    protected function provider(): array
    {
        return [
            'Test With all Ids and media video' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 3,
                'withMediaVideo' => true,
                'withMediaTrailer' => false,
                'withMediaThumb' => false,
                'withMediaHalf' => false,
                'withMediaBanner' => false,
            ],
            'Test With categories and genres and without files' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 0,
            ],
            'Test With all Ids and all media' => [
                'categories' => 2,
                'genres' => 2,
                'castMembers' => 2,
                'withMediaVideo' => true,
                'withMediaTrailer' => true,
                'withMediaThumb' => true,
                'withMediaHalf' => true,
                'withMediaBanner' => true,
            ],
            'Test Without Ids and all media' => [
                'categories' => 0,
                'genres' => 0,
                'castMembers' => 0,
                'withMediaVideo' => true,
                'withMediaTrailer' => true,
                'withMediaThumb' => true,
                'withMediaHalf' => true,
                'withMediaBanner' => true,
            ],
        ];
    }
}
