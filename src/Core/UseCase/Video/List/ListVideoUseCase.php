<?php

namespace Core\UseCase\Video\List;

use Core\Domain\Repository\VideoRepositoryInterface;

class ListVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {
        
    }
}