<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteInputDto;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteOutputDto;

class DeleteCategoryUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryDeleteInputDto $input): CategoryDeleteOutputDto
    {
        $response = $this->repository->delete($input->id);

        return new CategoryDeleteOutputDto(
            success: $response
        );
    }
}
