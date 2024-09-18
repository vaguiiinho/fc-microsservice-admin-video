<?php

namespace Core\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value, string $exceptionMessage = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptionMessage ?? "Should not be empty or null");
        }
    }

    public static function strMaxLength(string $value, int $maxLength = 255, string $exceptionMessage = null)
    {
        if (strlen($value) >= $maxLength) {
            throw new EntityValidationException($exceptionMessage ?? "The value must not be greater than {$maxLength} characters");
        }
    }

    public static function strMinLength(string $value, int $minLength = 3, string $exceptionMessage = null)
    {
        if (strlen($value) < $minLength) {
            throw new EntityValidationException($exceptionMessage ?? "The value must be at least {$minLength} characters");
        }
    }


    public static function strCanNullAndMaxLength(string $value = '', int $maxLength = 255, string $exceptionMessage = null) {
        if (!empty($value) && strlen($value) > $maxLength) {
            throw new EntityValidationException($exceptionMessage?? "The value must not be greater than {$maxLength} characters");
        }
    }
}
