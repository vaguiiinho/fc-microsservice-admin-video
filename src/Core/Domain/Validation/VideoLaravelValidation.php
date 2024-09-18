<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\Entity;

class VideoLaravelValidation implements ValidatorInterface
{
    public function validate(Entity $entity): void
    {
        $entity->notification->addErrors();
    }
}