<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends TestCase
{

    public function testAttributes() {
        $category = new Category(
            name: 'New cat',
            description: 'New cat description',
            isActive: true

        );
        $this->assertEquals('New cat', $category->name);
        $this->assertEquals('New cat description', $category->description);
        $this->assertTrue($category->isActive);
    }

    public function testActiveted() {
        $category = new Category(
            name: 'New cat',
            isActive: false
        );
        
        $this->assertFalse($category->isActive);

        $category->activate();

        $this->assertTrue($category->isActive);

    }

    public function testDisabled() {
        $category = new Category(
            name: 'New cat',
        );
        
        $this->assertTrue($category->isActive);
        
        $category->desable();
        
        $this->assertFalse($category->isActive);

    }
}