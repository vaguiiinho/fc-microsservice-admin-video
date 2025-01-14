<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class BaseVideoUseCaseUnitTest extends TestCase
{
    protected $useCase;

    abstract protected function nameActionRepository(): string;

    abstract protected function getUseCase(): string;

    abstract protected function createMockInputDto(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersId = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
    );

    protected function createUseCase(
        int $timesCallMethodActionRepository = 1,
        int $timesCallUpdateMediaRepository = 1,

        int $timesCallMethodCommitTransaction = 1,
        int $timesCallMethodRollbackTransaction = 0,

        int $timesCallMethodStore = 0,

        int $timesCallMethodDispatchEvent = 0,

    ) {
        $this->useCase = new ($this->getUseCase())(
            repository: $this->createMockRepository(
                timesCallAction: $timesCallMethodActionRepository,
                timesCallUpdateMedia: $timesCallUpdateMediaRepository,
            ),
            transaction: $this->createMockTransaction(
                timesCallCommit: $timesCallMethodCommitTransaction,
                timesCallRollback: $timesCallMethodRollbackTransaction,
            ),
            storage: $this->createMockFileStorage(
                times: $timesCallMethodStore,
            ),
            eventManager: $this->createMockEventManager(
                times: $timesCallMethodDispatchEvent,
            ),
            repositoryCategory: $this->createMockRepositoryCategory(),
            repositoryGenre: $this->createMockRepositoryGenre(),
            repositoryCastMember: $this->createMockRepositoryCastMember(),
        );
    }

    /**
     * @dataProvider dataProviderIds
     */
    public function test_exception_categories_ids(
        string $label,
        array $ids
    ) {
        $this->createUseCase(
            timesCallMethodActionRepository: 0,
            timesCallUpdateMediaRepository: 0,
            timesCallMethodCommitTransaction: 0,
        );

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

    /**
     * @dataProvider dataProvidersFiles
     */
    public function test_upload_files(
        array $video,
        array $trailer,
        array $thumb,
        array $thumbHalf,
        array $banner,
        int $timesStorages,
        int $timesManager = 0
    ) {
        $this->createUseCase(
            timesCallMethodStore: $timesStorages,
            timesCallMethodDispatchEvent: $timesManager
        );

        $response = $this->useCase->exec(
            input: $this->createMockInputDto(
                videoFile: $video['value'],
                trailerFile: $trailer['value'],
                thumbFile: $thumb['value'],
                thumbHalf: $thumbHalf['value'],
                bannerFile: $banner['value']
            )
        );

        $this->assertEquals($video['expected'], $response->videoFile);
        $this->assertEquals($trailer['expected'], $response->trailerFile);
        $this->assertEquals($thumb['expected'], $response->thumbFile);
        $this->assertEquals($thumbHalf['expected'], $response->thumbHalf);
        $this->assertEquals($banner['expected'], $response->bannerFile);
    }

    public function dataProvidersFiles(): array
    {
        return [
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'trailer' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumb' => ['value' => ['tmp' => 'tmp/file.jpg'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => ['tmp' => 'tmp/file.jpg'], 'expected' => 'path/file.png'],
                'banner' => ['value' => ['tmp' => 'tmp/banner. PNG'], 'expected' => 'path/file.png'],
                'timesStorages' => 5,
                'timesManager' => 1,
            ],
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.jpg'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'tmp/banner. PNG'], 'expected' => 'path/file.png'],
                'timesStorages' => 3,
                'timesManager' => 1,
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.jpg'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'tmp/banner. PNG'], 'expected' => 'path/file.png'],
                'timesStorages' => 2,
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => null, 'expected' => null],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => null, 'expected' => null],
                'timesStorages' => 0,
            ],
        ];
    }

    private function createMockRepository(
        int $timesCallAction,
        int $timesCallUpdateMedia,
    ) {
        $entity = $this->createEntity();

        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mock->shouldReceive($this->nameActionRepository())
            ->times($timesCallAction)
            ->andReturn($entity);

        $mock->shouldReceive('findById')
            ->andReturn($entity);

        $mock->shouldReceive('updateMedia')
            ->times($timesCallUpdateMedia);

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

    private function createMockTransaction(
        int $timesCallCommit,
        int $timesCallRollback
    ) {
        $mock = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mock->shouldReceive('commit')->times($timesCallCommit);
        $mock->shouldReceive('rollback')->times($timesCallRollback);

        return $mock;
    }

    private function createMockFileStorage(int $times)
    {
        $mock = Mockery::mock(stdClass::class, FileStorageInterface::class);
        $mock->shouldReceive('store')
            ->times($times)
            ->andReturn('path/file.png');

        return $mock;
    }

    private function createMockEventManager(int $times)
    {
        $mock = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mock->shouldReceive('dispatch')->times($times);

        return $mock;
    }

    private function createEntity()
    {
        return new Video(
            title: 'Title',
            description: 'Description',
            yearLaunched: 2026,
            duration: 1,
            opened: true,
            rating: Rating::RATE10
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
