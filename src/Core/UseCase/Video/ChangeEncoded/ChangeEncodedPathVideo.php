<?php

namespace Core\UseCase\Video\ChangeEncoded;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Media;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoDTO;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoOutputDTO;

class ChangeEncodedPathVideo
{
    public function __construct(
        protected VideoRepositoryInterface $repository
    ) {}

    public function exec(ChangeEncodedVideoDTO $input): void
    {
        $this->repository->findById($input->id);
       
    }
}
