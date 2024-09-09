<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\UseCase\DTO\CastMember\List\{
    ListCastMembersInputDto,
    ListCastMembersOutputDto
};
use Mockery;
use stdClass;
use Tests\Unit\UseCase\UseCaseTrait;
use PHPUnit\Framework\TestCase;

class ListCastMembersUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;
    public function testList()
    {
        // Arrange
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
            ->once()
            ->andReturn($this->mockPagination());

        $useCase = new ListCastMembersUseCase($mockRepository);

        $mockInput = Mockery::mock(ListCastMembersInputDto::class, [
            'test',
            "desc",
            1,
            15
        ]);
        // Action

        $response = $useCase->execute($mockInput);

        // Assert
        $this->assertInstanceOf(ListCastMembersOutputDto::class, $response);

        Mockery::close();
    }
}
