<?php

namespace Core\UseCase\DTO\Genre\List;

class ListGenreOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active = true,
        public string $created_at = ''
    ) {}
}
