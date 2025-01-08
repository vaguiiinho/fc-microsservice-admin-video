<?php

namespace Core\UseCase\Video\Delete\DTO;

class DeleteVideoInputDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
