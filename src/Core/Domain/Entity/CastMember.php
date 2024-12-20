<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\CastMemberType;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use Faker\Core\Number;

class CastMember extends Entity
{
    public function __construct(
        protected string $name,
        protected CastMemberType $type,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,
    ){
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();
        $this->validate();
    }

    public function update(string $name, ?int $type = null): void
    {
        $this->name = $name;
        if($type) {
            $this->type = CastMemberType::from($type);
        }
        $this->validate();
    }

    protected function validate()
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
    }
}