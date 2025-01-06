<?php

namespace Core\UseCase\Video\ChangeEncoded;

use Core\Domain\Repository\VideoRepositoryInterface;

class ChangeEncodedPathVideo
{
  public function __construct(protected VideoRepositoryInterface $repository) { }
}