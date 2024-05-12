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
            DomainValidation::notNull( $value);
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testNotNullMessageException()
    {
        try {
            $value = '';
            DomainValidation::notNull( $value, 'custom message error' );
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }


    public function testStrMaxLength()
    {
        try {
            $value = 'Test';
            DomainValidation::strMaxLength( $value, 3, 'custom message error');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }

    
    public function testStrMinLength()
    {
        try {
            $value = 'Te';
            DomainValidation::strMinLength( $value, 3, 'custom message error');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }

    public function testStrCanNullAndMaxLength()
    {
        try {
            $value = 'Test';
            DomainValidation::strCanNullAndMaxLength( $value, 3, 'custom message error');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }
}