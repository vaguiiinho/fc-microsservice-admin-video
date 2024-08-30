<?php

namespace Core\UseCase\DTO\Genre\Create;

class CreateGenreOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categoriesId = [],
        public bool $is_active,
        public string $createdAt = ''
    ) {
    }
}