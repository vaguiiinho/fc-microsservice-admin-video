<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\UseCase\DTO\CastMember\Create\CreateCastMemberInputDto;
use Core\UseCase\DTO\CastMember\Create\CreateCastMemberOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCastMemberUseCaseUnitTest extends TestCase
{
    public function test_create()
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

        $mockRepository->shouldReceive('insert')
            ->once()
            ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(CreateCastMemberInputDto::class, ['new cast member', 1]);

        $useCase = new CreateCastMemberUseCase($mockRepository);

        // Action
        $response = $useCase->execute($mockInputDto);

        // Assert
        $this->assertInstanceOf(CreateCastMemberOutputDto::class, $response);
        $this->assertEquals($uuid, $response->id);
        $this->assertEquals('new cast member', $response->name);
        $this->assertNotEmpty($response->created_at);
        $this->assertEquals(1, $response->type);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
