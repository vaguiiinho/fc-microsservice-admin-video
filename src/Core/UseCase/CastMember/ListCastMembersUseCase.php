<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\List\ListCastMembersInputDto;
use Core\UseCase\DTO\CastMember\List\ListCastMembersOutputDto;

class ListCastMembersUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCastMembersInputDto $input): ListCastMembersOutputDto
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        return new ListCastMembersOutputDto(
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
