<?php

namespace Core\UseCase\DTO\Category\ListCategory;

class CategoryOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description = '',
        public bool $is_active = true,
    ) {
    }
}