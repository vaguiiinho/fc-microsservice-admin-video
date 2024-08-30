<?php

namespace Core\UseCase\DTO\Genre\update;

class updateGenreOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active,
        public string $createdAt = ''
    ) {
    }
}