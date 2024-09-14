<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;

class Video
{
    use MethodsMagicsTrait;

    protected array $categoriesId = [];

    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Uuid $id = null,
        protected bool $published = false,
    ) 
    {
        $this->id = $this->id ?? Uuid::random() ;
    }

    public function addCategory(string $categoryId): void
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategory(string $categoryId): void
    {
        if (($key = array_search($categoryId, $this->categoriesId)) !== false) {
            unset($this->categoriesId[$key]);
        }
    }
}