<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\CastMember\ListCastMemberUseCase;
use Core\UseCase\DTO\CastMember\List\ListCastMemberInputDto;
use Core\UseCase\DTO\CastMember\List\ListCastMemberOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCastMemberUseCaseUnitTest extends TestCase
{
    public function test_list()
    {
        // Arrange
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);

        $mockEntity = Mockery::mock(CastMember::class, [
            'new cast member',
            CastMemberType::ACTOR,
        ]);

        $mockEntity->shouldReceive('id')->andReturn(new ValueObjectUuid($uuid));
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($uuid)
            ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(ListCastMemberInputDto::class, [$uuid]);

        $useCase = new ListCastMemberUseCase($mockRepository);

        // Action
        $response = $useCase->execute($mockInputDto);
        // Assert
        $this->assertInstanceOf(ListCastMemberOutputDto::class, $response);
        $this->assertEquals($uuid, $response->id);
        $this->assertEquals('new cast member', $response->name);
        $this->assertNotEmpty($response->created_at);
        $this->assertEquals(2, $response->type);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
