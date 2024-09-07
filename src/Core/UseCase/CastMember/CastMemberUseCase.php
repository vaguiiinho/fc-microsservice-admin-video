<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\Create\{
    CreateCastMemberInputDto,
    CreateCastMemberOutputDto
};

class CastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateCastMemberInputDto $input): CreateCastMemberOutputDto
    {
        
    }
}
