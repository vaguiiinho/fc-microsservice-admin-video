<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\CastMemberUseCase;
use Core\UseCase\DTO\CastMember\Create\{
    CreateCastMemberInputDto,
    CreateCastMemberOutputDto
};
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CastMemberUseCaseUnitTest extends TestCase
{
    public function testCreate()
    {
        // Arrange
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockInputDto = Mockery::mock(CreateCastMemberInputDto::class, ['new cast member', 1]);

        $useCase = new CastMemberUseCase($mockRepository);



        // Action
        $response =   $useCase->execute($mockInputDto);


        // Assert
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $response);
        $this->assertInstanceOf(CreateCastMemberOutputDto::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
