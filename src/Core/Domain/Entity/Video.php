<?php

namespace Core\Domain\Entity;

use Core\Domain\ValueObject\Uuid;

class Video
{
    public function __construct(
        protected Uuid $id,
        protected string $title,
        protected string $description,
        
    ) 
    {
        
    }
}