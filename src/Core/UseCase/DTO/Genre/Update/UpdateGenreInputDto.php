<?php

namespace Core\UseCase\DTO\Genre\Update;

class UpdateGenreInputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categoriesId = [],
        public bool $isActive = true
    ) {
    }
}