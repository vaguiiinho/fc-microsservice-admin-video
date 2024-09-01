<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\Delete\{
    DeleteGenreInputDto,
    DeleteGenreOutputDto
};


class DeleteGenreUseCase
{

    protected $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteGenreInputDto $input): DeleteGenreOutputDto
    {
        $response = $this->repository->delete($input->id);

        return new DeleteGenreOutputDto(
            success: $response
        );
    }
}