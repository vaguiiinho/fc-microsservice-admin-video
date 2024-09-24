<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CategoryRepositoryInterface,
    VideoRepositoryInterface,
    GenreRepositoryInterface,
    CastMemberRepositoryInterface,
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Create\CreateVideoUseCase as UseCase;
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateVideoUseCaseUnitTest extends TestCase
{
    protected $useCase;
    public function setUp(): void
    {
        $this->useCase = new UseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockFileStorage(),
            eventManager: $this->createMockEventManager(),
            repositoryCategory: $this->createMockRepositoryCategory(),
            repositoryGenre: $this->createMockRepositoryGenre(),
            repositoryCastMember: $this->createMockRepositoryCastMember(),
        );

        parent::setUp();
    }

    public function test_exec_input_output()
    {
        $response =   $this->useCase->exec(
            input: $this->createMockInputDto()
        );

        $this->assertInstanceOf(CreateOutputVideoDTO::class, $response);
    }


    /** 
     * @dataProvider dataProviderIds
     */
    public function test_exception_categories_ids(
        string $label,
        array $ids
    ) {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf(
            '%s %s not found',
            $label,
            implode(', ', $ids)
        ));

        $this->useCase->exec(
            input: $this->createMockInputDto(
                categoriesIds: $ids
            )
        );
    }

    public function dataProviderIds(): array
    {
        return [
            ['Category', ['uuid-1']],
            ['Categories', ['uuid-1', 'uuid-2']],
            ['Categories', ['uuid-1', 'uuid-2', 'uuid-3', 'uuid-4']],
        ];
    }

    public function test_upload_files()
    {
        $response =   $this->useCase->exec(
            input: $this->createMockInputDto(
                videoFile: ['tmp' => 'tmp/file.png'],
            )
        );

        $this->assertNotNull($response->videoFile);
    }

    private function createMockRepository()
    {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mock->shouldReceive('insert')
            // ->once()
            ->andReturn($this->createMockEntity());
        $mock->shouldReceive('updateMedia');
        return $mock;
    }

    private function createMockRepositoryCategory(array $categoriesId = [])
    {
        $mock = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')
            // ->once()
            ->andReturn($categoriesId);
        return $mock;
    }

    private function createMockRepositoryGenre(array $genresId = [])
    {
        $mock = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')
            // ->once()
            ->andReturn($genresId);
        return $mock;
    }

    private function createMockRepositoryCastMember(array $castMembersId = [])
    {
        $mock = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mock->shouldReceive('getIdsListIds')
            // ->once()
            ->andReturn($castMembersId);
        return $mock;
    }

    private function createMockTransaction()
    {
        $mock = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollback');
        return $mock;
    }

    private function createMockFileStorage()
    {
        $mock = Mockery::mock(stdClass::class, FileStorageInterface::class);
        $mock->shouldReceive('store')
            ->andReturn('path/file.png');
        return $mock;
    }

    private function createMockEventManager()
    {
        $mock = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mock->shouldReceive('dispatch');
        return $mock;
    }

    private function createMockInputDto(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersId = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
    ) {
        return Mockery::mock(CreateInputVideoDTO::class, [
            'Test Video',
            'Test Description',
            2022,
            120,
            true,
            Rating::RATE10,
            $categoriesIds,
            $genresIds,
            $castMembersId,
            $videoFile,
            $trailerFile,
            $thumbFile,
            $thumbHalf,
            $bannerFile,
        ]);
    }

    private function createMockEntity()
    {
        return Mockery::mock(Video::class, [
            'Test Video',
            'Test Description',
            2022,
            120,
            true,
            Rating::RATE10,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
