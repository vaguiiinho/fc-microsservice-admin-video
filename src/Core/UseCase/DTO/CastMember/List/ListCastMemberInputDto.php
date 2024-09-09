<?php

namespace Core\UseCase\DTO\CastMember\List;

class ListCastMemberInputDto
{
    public function __construct(
        public string $id = '',
    ) {
    }
}