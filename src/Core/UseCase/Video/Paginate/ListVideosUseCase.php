<?php

namespace Core\UseCase\Video\Paginate;

use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\DTO\PaginateVideosInputDto;

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {}

    public function exec(PaginateVideosInputDto $input): PaginationInterface
    {
        return $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );
    }
}
