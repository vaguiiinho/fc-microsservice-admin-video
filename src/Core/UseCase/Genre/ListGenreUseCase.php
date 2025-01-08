<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\List\ListGenreInputDto;
use Core\UseCase\DTO\Genre\List\ListGenreOutputDto;

class ListGenreUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListGenreInputDto $input): ListGenreOutputDto
    {

        $genres = $this->repository->findById($input->id);

        return new ListGenreOutputDto(
            id: $genres->id(),
            name: $genres->name,
            is_active: $genres->isActive,
            created_at: $genres->createdAt()
        );
    }
}
