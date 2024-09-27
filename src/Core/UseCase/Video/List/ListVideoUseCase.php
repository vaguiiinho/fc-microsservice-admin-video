<?php

namespace Core\UseCase\Video\List;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\DTO\{
    ListVideoInputDto,
    ListVideoOutputDto
};

class ListVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {
        
    }

    public function exec(ListVideoInputDto $input): ListVideoOutputDto
    {

    }
}