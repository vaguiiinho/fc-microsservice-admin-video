<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\CastMember\UpdateCastMemberUseCase;
use Core\UseCase\DTO\CastMember\Update\{
    UpdateCastMemberInputDto,
    UpdateCastMemberOutputDto
};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateCastMemberUnitTest extends TestCase
{
    public function testUpdate()
    {
        // Arrange
        $uuid = (string) Uuid::uuid4();

        $mockEntity = Mockery::mock(CastMember::class, [
            'new cast member',
            CastMemberType::ACTOR,
            new ValueObjectUuid($uuid)
        ]);
        $mockEntity->shouldReceive('id')->andReturn(new ValueObjectUuid($uuid));
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update')->once();
            
        $mockRepository = Mockery::mock(
            stdClass::class,
            CastMemberRepositoryInterface::class
        );

        $mockRepository->shouldReceive('findById')
        ->once()
        ->with($uuid)
        ->andReturn($mockEntity);

        $mockRepository->shouldReceive('update')
            ->once()
            ->andReturn($mockEntity);

        $mockInput = Mockery::mock(UpdateCastMemberInputDto::class, [
            $uuid,
            'update cast member',
        ]);

        $useCase = new UpdateCastMemberUseCase($mockRepository);
        // Action
        $response = $useCase->execute($mockInput);

        // Assert
        $this->assertInstanceOf(UpdateCastMemberOutputDto::class, $response);

        // Teardown
        Mockery::close();
    }
}
