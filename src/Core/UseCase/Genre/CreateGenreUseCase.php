<?php

namespace Core\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    GenreRepositoryInterface,
    CategoryRepositoryInterface
};
use Core\UseCase\DTO\Genre\Create\{
    CreateGenreInputDto,
    CreateGenreOutputDto
};
use Core\UseCase\Interfaces\TransactionInterface;
use Throwable;

class CreateGenreUseCase
{
    protected $repository;
    protected $transaction;
    protected $categoryRepository;

    public function __construct(
        GenreRepositoryInterface $repository,
        TransactionInterface $transaction,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(CreateGenreInputDto $input): CreateGenreOutputDto
    {
        try {
            $genre = new Genre(
                name: $input->name,
                isActive: $input->isActive,
                categoriesId: $input->categoriesId
            );

            $this->validateCategoriesId($input->categoriesId);

            $genreDb = $this->repository->insert($genre);

            return new CreateGenreOutputDto(
                id: $genreDb->id(),
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                createdAt: $genreDb->createdAt()
            );

            $this->transaction->commit();
        } catch (Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoryId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoryId);

        if (count($categoriesDb) !== count($categoryId)) {
            throw new NotFoundException('categories not found');
        }
    }
}
