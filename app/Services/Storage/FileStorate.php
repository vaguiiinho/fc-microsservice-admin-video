<?php

namespace App\Services\Storate;

use Core\UseCase\Interfaces\FileStorageInterface;

class FileStorage implements FileStorageInterface
{
    /**
     * @param string $path
     * @param array $_FILES[file]
     */
    public function store(string $path, array $file): string
    {

    }
    
    public function delete(string $path)
    {

    }
    
}

