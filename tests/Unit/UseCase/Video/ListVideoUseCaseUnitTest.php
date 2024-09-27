<?php   

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\ListVideoUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListVideoUseCaseUnitTest extends TestCase
{
    public function test_list()
    {
        // Arrange
        $useCase = new ListVideoUseCase(
            repository: $this->mockRepository()
        );

        // Act

        // Assert
        $this->assertTrue(true);
        
    }

    private function mockRepository()
    {
        return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

}