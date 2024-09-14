<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;

class Video
{
    use MethodsMagicsTrait;

    protected array $categoriesId = [];
    protected array $genresId = [];
    protected array $castMembersId = [];

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

    public function addGenre(string $genreId): void
    {
        array_push($this->genresId, $genreId);
    }

    public function removeGenre(string $genreId): void
    {
        if (($key = array_search($genreId, $this->genresId)) !== false) {
            unset($this->genresId[$key]);
        }
    }

    public function addCastMember(string $castMemberId): void
    {
        array_push($this->castMembersId, $castMemberId);
    }

    public function removeCastMember(string $castMemberId): void
    {
        if (($key = array_search($castMemberId, $this->castMembersId)) !== false) {
            unset($this->castMembersId[$key]);
        }
    }
}