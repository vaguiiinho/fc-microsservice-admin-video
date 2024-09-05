<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\CastMemberType;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class CastMember
{
    public function __construct(
        protected ?Uuid $id = null,
        protected string $name,
        protected CastMemberType $type,
        protected ?DateTime $createAt = null,
    ){
        $this->id = $this->id ?? Uuid::random();
        $this->createAt = $this->createAt ?? new DateTime();
    }
}