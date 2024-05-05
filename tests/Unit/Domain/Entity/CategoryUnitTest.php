<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends TestCase
{

    public function testAttributes() {
        $category = new Category(
            id: '123',
            name: 'New cat',
            description: 'New cat description',
            isActive: true

        );
        $this->assertEquals('New cat', $category->name);
        $this->assertEquals('New cat description', $category->description);
        $this->assertEquals(true, $category->isActive);
    }
}