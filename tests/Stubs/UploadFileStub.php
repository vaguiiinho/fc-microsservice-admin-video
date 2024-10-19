<?php

namespace Tests\Stubs;

use Core\UseCase\Interfaces\FileStorageInterface;

class UploadFileStub implements FileStorageInterface
{
    public function __construct()
    {
        event($this);
    }

    public function store(string $path, array $file): string
    {
        return "{$path}/test.mp4";
    }

    public function delete(string $path) {}
}
