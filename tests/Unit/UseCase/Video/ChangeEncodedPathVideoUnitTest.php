<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoDTO;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ChangeEncodedPathVideoUnitTest extends TestCase
{
    public function test_example()
    {
        $input = new ChangeEncodedVideoDTO(
            id: 'id-video',
            encodedPath: 'path/video_encoded.ext',
        );

        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('findById')
            ->times(1)
            ->with($input->id)
            ->andReturn($this->getEntity());

        $useCase = new ChangeEncodedPathVideo(
            repository: $mockRepository
        );

        $response = $useCase->exec(input: $input);

        Mockery::close();
    }

    private function getEntity(): Entity
    {
        return new Entity(
            title: 'title',
            description: 'desc',
            yearLaunched: 2026,
            duration: 1,
            opened: true,
            rating: Rating::L
        );
    }
}