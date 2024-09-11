<?php

namespace Core\UseCase\DTO\CastMember\List;

class ListCastMemberOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $createdAt = '',
    ) {
    }
}
