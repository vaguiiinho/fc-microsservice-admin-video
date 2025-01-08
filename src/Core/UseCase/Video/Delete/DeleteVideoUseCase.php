<?php

namespace Core\UseCase\Video\Delete;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Delete\DTO\DeleteVideoInputDto;
use Core\UseCase\Video\Delete\DTO\DeleteVideoOutputDto;

class DeleteVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {}

    public function execute(DeleteVideoInputDto $input): DeleteVideoOutputDto
    {
        $response = $this->repository->delete($input->id);

        return new DeleteVideoOutputDto(
            success: $response
        );
    }
}
