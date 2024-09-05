<?php

namespace Core\Domain\Entity;

use Core\Domain\ValueObject\Uuid;
use DateTime;

class CastMember
{
    public function __construct(
        public ?Uuid $id = null,
        public string $name,
        public ?DateTime $createAt = null,
    ){
        $this->id = $this->id ?? Uuid::random();
        $this->createAt = $this->createAt ?? new DateTime();
    }
}