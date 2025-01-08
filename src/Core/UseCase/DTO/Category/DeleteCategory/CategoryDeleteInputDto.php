<?php

namespace Core\UseCase\DTO\Category\DeleteCategory;

class CategoryDeleteInputDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
