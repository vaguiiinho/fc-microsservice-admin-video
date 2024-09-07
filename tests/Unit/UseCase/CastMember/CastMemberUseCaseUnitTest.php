<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\CastMember\CastMemberUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CastMemberUseCaseUnitTest extends TestCase
{
    public function testCreate()
    {
        // Arrange
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $useCase = new CastMemberUseCase($mockRepository);

        // Action
        $useCase->execute();


        // Assert
        $this->assertTrue(true);

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}
