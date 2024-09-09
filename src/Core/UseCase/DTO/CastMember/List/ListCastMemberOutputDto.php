<?php

namespace Core\UseCase\DTO\CastMember\List;

class ListCastMemberOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $created_at = '',
        public int $type
    ) {
    }
}
