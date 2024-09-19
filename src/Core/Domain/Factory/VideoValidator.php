<?php

namespace Core\Domain\Factory;

use Core\Domain\Validation\ValidatorInterface;
use Core\Domain\Validation\VideoLaravelValidation;

class VideoValidator
{
    public  static function create(): ValidatorInterface
    {
        return new VideoLaravelValidation();
    }
}