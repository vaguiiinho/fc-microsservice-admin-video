<?php

namespace Core\UseCase\Video\Paginate;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\DTO\{
    PaginateVideosInputDto,
    PaginateVideosOutputDto
};

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {}

    public function exec(PaginateVideosInputDto $input): PaginateVideosOutputDto
    {
        return new PaginateVideosOutputDto();
    }
}
