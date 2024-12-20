<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Update\{
    UpdateCastMemberInputDto,
    UpdateCastMemberOutputDto
};

class UpdateCastMemberUseCase
{
    protected $repository;

    public function __construct(
        CastMemberRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function execute(UpdateCastMemberInputDto $input): UpdateCastMemberOutputDto
    {
        $castMember = $this->repository->findById($input->id);
        $castMember->update(
            name: $input->name,
            type: $input->type,
        );
        $response = $this->repository->update($castMember);
        return new UpdateCastMemberOutputDto(
            id: $response->id(),
            name: $response->name,
            type: $response->type->value,
            created_at: $response->createdAt()
        );
    }
}
