<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\Delete\DTO\{
    DeleteVideoInputDto,
    DeleteVideoOutputDto
};
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class DeleteVideoUseCaseUnitTest extends TestCase
{

    /**
     * @dataProvider dataProviders
     */
    public function test_delete(
        array $id,
    )
    {
        $useCase = new DeleteVideoUseCase(
            repository: $this->mockRepository($id['expected'])
        );
        $responseUseCase = $useCase->execute($this->mockInputDto($id['value']));

        $this->assertInstanceOf(DeleteVideoOutputDto::class, $responseUseCase);
        $this->assertEquals($id['expected'], $responseUseCase->success);
    }

    public function dataProviders(): array
    {
        $id = Uuid::random();

        return [
            [
                'id' => ['value' => $id, 'expected' => true],
            ],
            [
                'id' => ['value' => 'fake', 'expected' => false],
            ],
        ];
    }

    private function mockInputDto($id)
    {
        return Mockery::mock(DeleteVideoInputDto::class, [$id]);
    }

    private function mockRepository(bool $expected)
    {
        $mockRepo = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepo->shouldReceive('delete')
            ->once()
            ->andReturn($expected);

        return $mockRepo;
    }

    protected function teardown(): void
    {
        Mockery::close();
        parent::teardown();
    }
}
