<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\CastMember\ListCastMemberUseCase;
use Core\UseCase\DTO\CastMember\List\{
    ListCastMemberInputDto,
    ListCastMemberOutputDto
};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCastMemberUseCaseUnitTest extends TestCase
{
    public function testList()
    {
        // Arrange
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);

        $mockEntity = Mockery::mock(CastMember::class, [
            'new cast member',
            CastMemberType::ACTOR,
        ]);

        $mockEntity->shouldReceive('id')->andReturn(new ValueObjectUuid($uuid));
        $mockEntity->shouldReceive('createdAt')->andReturn(Date('Y-m-d H:i:s'));

        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($uuid)
            ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(ListCastMemberInputDto::class, [$uuid]);

        $useCase = new ListCastMemberUseCase($mockRepository);



        // Action
        $response =   $useCase->execute();

        // Assert
        
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
