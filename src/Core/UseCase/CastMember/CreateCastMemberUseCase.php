<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Create\{
    CreateCastMemberInputDto,
    CreateCastMemberOutputDto
};

class CreateCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateCastMemberInputDto $input): CreateCastMemberOutputDto
    {
        $castMember = new CastMember(
            name: $input->name,
            type: CastMemberType::from($input->type),
        );

        $response = $this->repository->insert($castMember);

        return new CreateCastMemberOutputDto(
            id: $response->id(),
            name: $response->name,
            type: $input->type,
            created_at: $response->createdAt()
        );
    }
}
