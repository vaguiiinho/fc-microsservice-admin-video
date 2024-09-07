<?php

namespace Core\UseCase\DTO\CastMember\Create;


class CreateCastMemberInputDto
{
    public function __construct(
        public string $name,
        public int $type,
    ) {}
}
