<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\Factory\VideoValidator;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Video extends Entity
{
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
        protected ?DateTime $createdAt = null,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalf = null,
        protected ?Image $bannerFile = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
    ) {
        parent::__construct();
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime;
        $this->validation();
    }

    public function update(string $title, string $description): void
    {
        $this->title = $title;
        $this->description = $description;

        $this->validation();
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

    public function thumbFile(): ?Image
    {
        return $this->thumbFile;
    }

    public function thumbHalf(): ?Image
    {
        return $this->thumbHalf;
    }

    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }

    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }

    public function setVideoFile(Media $videoFile): void
    {
        $this->videoFile = $videoFile;
    }

    public function setTrailerFile(Media $trailerFile): void
    {
        $this->trailerFile = $trailerFile;
    }

    public function setThumbFile(Image $thumbFile): void
    {
        $this->thumbFile = $thumbFile;
    }

    public function setThumbHalf(Image $thumbHalf): void
    {
        $this->thumbHalf = $thumbHalf;
    }

    public function setBannerFile(Image $bannerFile): void
    {
        $this->bannerFile = $bannerFile;
    }

    protected function validation()
    {
        VideoValidator::create()->validate($this);

        if ($this->notification->hasErrors()) {
            throw new NotificationException(
                $this->notification->messages('video')
            );
        }
    }
}
