<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTraid;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;

class Category
{
    use MethodsMagicsTraid;

    public function __construct(
        protected string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = true
    ) {
        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function desable(): void
    {
        $this->isActive = false;
    }

    public function update(string $name, string $description = '')
    {
        $this->name = $name;
        $this->description = $description;

        $this->validate();
    }

    public function validate()
    {
       DomainValidation::strMaxLength($this->name);
       DomainValidation::strMinLength($this->name);
       DomainValidation::strCanNullAndMaxLength($this->description);
    }
}
