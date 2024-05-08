<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTraid;
use Core\Domain\Exception\EntityValidationException;

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
        if (empty($this->name)) {
            throw new EntityValidationException("nome invalido");
        }

        if (strlen($this->name) > 255 || strlen($this->name) <= 2) {
            throw new EntityValidationException("nome invalido");
        }

        if ($this->description != '' && (strlen($this->description) > 255 || strlen($this->description) < 3)) {
            throw new EntityValidationException("descrição invalido");
        }
    }
}
