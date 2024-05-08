<?php

namespace Tests\Unit\Domain\Validation;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;
use Throwable;

class DomainValidationUnitTest extends TestCase
{
    
    public function testNotNull()
    {
        try {
            $value = '';
            DomainValidation::notNull( $value );
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }
}