<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDto;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesOutputDto;

class ListCategoriesUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCategoriesInputDto $input): ListCategoriesOutputDto
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        return new ListCategoriesOutputDto(
            items: $response->items(),
            total: $response->total(),
            first_page: $response->firstPage(),
            last_page: $response->lastPage(),
            current_page: $response->currentPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from()
        );
    }
}
