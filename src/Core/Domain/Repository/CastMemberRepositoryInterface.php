<?php

namespace Core\Domain\Repository;

interface CastMemberRepositoryInterface extends EntityRepositoryInterface
{
    public function getIdsListIds(array $castMemberId = []): array;
}
