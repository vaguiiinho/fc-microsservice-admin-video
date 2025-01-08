<?php

namespace Core\UseCase\DTO\Genre\Delete;

class DeleteGenreInputDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
