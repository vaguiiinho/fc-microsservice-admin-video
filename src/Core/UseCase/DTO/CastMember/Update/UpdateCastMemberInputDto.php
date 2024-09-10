<?php

namespace Core\UseCase\DTO\CastMember\Update;


class UpdateCastMemberInputDto
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
