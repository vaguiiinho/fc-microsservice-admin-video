<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationExcepition;
use PHPUnit\Framework\TestCase;
use Throwable;

class CategoryUnitTest extends TestCase
{

    public function testAttributes()
    {
        $category = new Category(
            name: 'New cat',
            description: 'New cat description',
            isActive: true

        );
        $this->assertEquals('New cat', $category->name);
        $this->assertEquals('New cat description', $category->description);
        $this->assertTrue($category->isActive);
    }

    public function testActiveted()
    {
        $category = new Category(
            name: 'New cat',
            isActive: false
        );

        $this->assertFalse($category->isActive);

        $category->activate();

        $this->assertTrue($category->isActive);
    }

    public function testDisabled()
    {
        $category = new Category(
            name: 'New cat',
        );

        $this->assertTrue($category->isActive);

        $category->desable();

        $this->assertFalse($category->isActive);
    }


    public function testUpdate()
    {
        $uuid = 'uudi.value';
        $category = new Category(
            id: $uuid,
            name: 'New cat',
            description: 'New cat description',
            isActive: true

        );

        $category->update(
            name: 'new_name',
            description: 'new_description',
        );

        $this->assertEquals('new_name', $category->name);
        $this->assertEquals('new_description', $category->description);
    }

    public function testExceptionName()
    {
        try {
            $category = new Category(
                name: 'Ne',
                description: 'New cat description',
            );
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationExcepition::class, $th);
        }
    }
}
