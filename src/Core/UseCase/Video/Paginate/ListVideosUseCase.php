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
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        return new PaginateVideosOutputDto(
            items: $response->items(),
            total: $response->total(),
            current_page: $response->currentPage(),
            first_page: $response->firstPage(),
            last_page: $response->lastPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from()
        );
    }
}
