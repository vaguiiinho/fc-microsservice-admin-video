<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Core\UseCase\DTO\CastMember\Delete\DeleteCastMemberInputDto;
use Core\UseCase\DTO\CastMember\Delete\DeleteCastMemberOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class DeleteCastMemberUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        // Arrange
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);

        $mockRepository->shouldReceive('delete')
            ->with($uuid)
            ->once()
            ->andReturn(true);

        $mockInputDto = Mockery::mock(DeleteCastMemberInputDto::class, [$uuid]);

        $useCase = new DeleteCastMemberUseCase($mockRepository);

        // Action
        $response =   $useCase->execute($mockInputDto);

        // Assert
        $this->assertInstanceOf(DeleteCastMemberOutputDto::class, $response);
        $this->assertTrue($response->success);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
