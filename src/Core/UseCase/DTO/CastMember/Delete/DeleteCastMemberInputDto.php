<?php

namespace Core\UseCase\DTO\CastMember\Delete;

class DeleteCastMemberInputDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
