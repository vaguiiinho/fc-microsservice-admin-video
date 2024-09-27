<?php

namespace Core\UseCase\Video\ListAll;

use Core\Domain\Repository\VideoRepositoryInterface;

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {}
}
