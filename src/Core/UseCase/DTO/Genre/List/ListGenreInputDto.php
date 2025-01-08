<?php

namespace Core\UseCase\DTO\Genre\List;

class ListGenreInputDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
