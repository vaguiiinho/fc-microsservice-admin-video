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
    public function test_create()
    {
        $useCase = new CreateVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            $this->app->make(FileStorageInterface::class),
            $this->app->make(VideoEventManagerInterface::class),
            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class)
        );

        $categoriesIds = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count(3)->create()->pluck('id')->toArray();
        $castMemberIds = CastMember::factory()->count(3)->create()->pluck('id')->toArray();

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

        $this->assertCount(count($input->categories), $response->categories);
        $this->assertCount(count($input->genres), $response->genres);
        $this->assertCount(count($input->castMembers), $response->castMembers);

        $this->assertNotNull($response->videoFile);
        $this->assertNull($response->trailerFile);
        $this->assertNull($response->thumbFile);
        $this->assertNull($response->thumbHalf);
        $this->assertNull($response->bannerFile);
    }
}
