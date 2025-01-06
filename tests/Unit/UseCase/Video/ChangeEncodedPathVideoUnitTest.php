<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ChangeEncodedPathVideoUnitTest extends TestCase
{
    
    public function test_example()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $useCase = new ChangeEncodedPathVideo(
            repository: $mockRepository
        );

        Mockery::close();
    }
}
