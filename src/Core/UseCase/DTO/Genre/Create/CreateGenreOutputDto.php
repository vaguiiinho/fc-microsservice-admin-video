<?php

namespace Core\UseCase\DTO\Genre\Create;

class CreateGenreOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active,
        public array $categoriesId = [],
        public string $createdAt = ''
    ) {}
}
