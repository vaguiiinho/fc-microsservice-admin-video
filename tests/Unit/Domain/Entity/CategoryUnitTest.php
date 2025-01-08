<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Throwable;

class CategoryUnitTest extends TestCase
{
    public function test_attributes()
    {
        $category = new Category(
            name: 'New cat',
            description: 'New cat description',
            isActive: true

        );

        $this->assertNotEmpty($category->createdAt());
        $this->assertNotEmpty($category->id());
        $this->assertEquals('New cat', $category->name);
        $this->assertEquals('New cat description', $category->description);
        $this->assertTrue($category->isActive);
    }

    public function test_activated()
    {
        $category = new Category(
            name: 'New cat',
            isActive: false
        );

        $this->assertFalse($category->isActive);

        $category->activate();

        $this->assertTrue($category->isActive);
    }

    public function test_disabled()
    {
        $category = new Category(
            name: 'New cat',
        );

        $this->assertTrue($category->isActive);

        $category->disable();

        $this->assertFalse($category->isActive);
    }

    public function test_update()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $category = new Category(
            id: $uuid,
            name: 'New cat',
            description: 'New cat description',
            isActive: true,
            createdAt: '2023-01-01 12:12:12'
        );

        $category->update(
            name: 'new_name',
            description: 'new_description',
        );

        $this->assertEquals($uuid, $category->id());
        $this->assertEquals('new_name', $category->name);
        $this->assertEquals('new_description', $category->description);
    }

    public function test_exception_name()
    {
        try {
            new Category(
                name: 'Ne',
                description: 'New cat description',
            );
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function test_exception_description()
    {
        try {
            new Category(
                name: 'Name Cat',
                description: random_bytes(999999),
            );
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }
}
