<?php

namespace Core\UseCase\DTO\Category\ListCategory;

class CategoryInputDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
