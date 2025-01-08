<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;
use Throwable;

class DomainValidationUnitTest extends TestCase
{
    public function test_not_null()
    {
        try {
            $value = '';
            DomainValidation::notNull($value);
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function test_not_null_message_exception()
    {
        try {
            $value = '';
            DomainValidation::notNull($value, 'custom message error');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }

    public function test_str_max_length()
    {
        try {
            $value = 'Test';
            DomainValidation::strMaxLength($value, 3, 'custom message error');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }

    public function test_str_min_length()
    {
        try {
            $value = 'Te';
            DomainValidation::strMinLength($value, 3, 'custom message error');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }

    public function test_str_can_null_and_max_length()
    {
        try {
            $value = 'Test';
            DomainValidation::strCanNullAndMaxLength($value, 3, 'custom message error');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }
}
