<?php

namespace Core\Domain\Entity;

use Core\Domain\ValueObject\Uuid;
use DateTime;

class Genre
{
    public function __construct(
        protected Uuid|null $id = null,
        protected string $name,
        protected bool $isActive = true,
        protected DateTime|null $createAt = null
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createAt = $this->createAt ?? new DateTime();
    }
}
