<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Entity;
use Core\Domain\Enum\MediaStatus;

interface Builder
{
    public function createEntity(object $object): Builder;

    public function addMediaVideo(string $path, MediaStatus $mediaStatus, string $encodedPath = ''): Builder;

    public function addTrailer(string $path): Builder;

    public function addThumb(string $path): Builder;

    public function addThumbHalf(string $path): Builder;

    public function addBanner(string $path): Builder;

    public function getEntity(): Entity;
}
