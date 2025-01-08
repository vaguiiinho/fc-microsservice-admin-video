<?php

namespace Core\UseCase\Video\Delete\DTO;

class DeleteVideoOutputDto
{
    public function __construct(
        public bool $success = true,
        public bool $error = false,
    ) {}
}
