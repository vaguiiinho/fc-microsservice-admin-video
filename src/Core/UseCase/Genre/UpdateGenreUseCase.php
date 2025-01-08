<?php

namespace Core\UseCase\Genre;

use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\Update\UpdateGenreInputDto;
use Core\UseCase\DTO\Genre\Update\UpdateGenreOutputDto;
use Core\UseCase\Interfaces\TransactionInterface;
use Throwable;

class UpdateGenreUseCase
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

    public function execute(UpdateGenreInputDto $input): UpdateGenreOutputDto
    {
        $genre = $this->repository->findById($input->id);
        try {
            $genre->update(
                name: $input->name,
            );

            foreach ($input->categoriesId as $categoryId) {
                $genre->addCategory($categoryId);
            }
            $this->validateCategoriesId($input->categoriesId);

            $genreDb = $this->repository->update($genre);

            $this->transaction->commit();

            return new UpdateGenreOutputDto(
                id: $genreDb->id(),
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                createdAt: $genreDb->createdAt()
            );
        } catch (Throwable $th) {

            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
        }
    }
}
