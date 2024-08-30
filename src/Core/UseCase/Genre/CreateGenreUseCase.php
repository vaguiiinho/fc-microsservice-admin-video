<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\Create\{
    CreateGenreInputDto,
    CreateGenreOutputDto
};
use Core\UseCase\Interfaces\TransactionInterface;

class CreateGenreUseCase
{
    protected $repository;
    protected $transaction;

    public function __construct(
        GenreRepositoryInterface $repository,
        TransactionInterface $transaction
    ) {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function execute(CreateGenreInputDto $input) {}
}
