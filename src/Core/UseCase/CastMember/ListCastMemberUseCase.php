<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\List\ListCastMemberInputDto;
use Core\UseCase\DTO\CastMember\List\ListCastMemberOutputDto;

class ListCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCastMemberInputDto $input)
    {
        $response = $this->repository->findById($input->id);

        return new ListCastMemberOutputDto(
            id: $response->id(),
            name: $response->name,
            type: $response->type->value,
            created_at: $response->createdAt()
        );
    }
}
