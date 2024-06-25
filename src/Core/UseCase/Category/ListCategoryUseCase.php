<?php

namespace Core\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\ListCategory\{
    CategoryInputDto,
    CategoryOutputDto
};


class ListCategoryUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryInputDto $input): CategoryOutputDto
    {
        $response = $this->repository->findById($input->id);

        return new CategoryOutputDto(
            id: $response->id(),
            name: $response->name,
            description: $response->description,
            is_active: $response->isActive,
            created_at: $response->createdAt()
        );
    }
}