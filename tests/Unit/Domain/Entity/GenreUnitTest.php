<?php

namespace Tests\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Genre;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use DateTime;
use Ramsey\Uuid\Uuid;

class GenreUnitTest extends TestCase
{

    public function testAttributes()
    {
        $uuid = (string) Uuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id: new ValueObjectUuid($uuid),
            name: 'New genre',
            isActive: false,
            createdAt: new DateTime($date),
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('New genre', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertEquals($date, $genre->createdAt());
    }

    public function testAttributesCreated()
    {
        $genre = new Genre(
            name: 'New genre',
        );

        $this->assertNotEmpty($genre);
        $this->assertEquals('New genre', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }


    public function testGenreDeactivate()
    {
        $genre = new Genre(
            name: 'New genre',
        );

        $this->assertTrue($genre->isActive);

        $genre->deactivate();

        $this->assertFalse($genre->isActive);
    }

    public function testGenreActivate()
    {
        $genre = new Genre(
            name: 'New genre',
            isActive: false,
        );

        $this->assertFalse($genre->isActive);

        $genre->Activate();

        $this->assertTrue($genre->isActive);
    }

    public function testGenreUpdate()
    {
        $genre = new Genre(
            name: 'New genre',
        );

        $this->assertEquals('New genre', $genre->name);

        $genre->update(
            name: 'Updated genre',
        );

        $this->assertEquals('Updated genre', $genre->name);
    }

    public function testEntityException()
    {
        $this->expectException(EntityValidationException::class);
        new Genre(
            name: 'a',
        );
    }

    public function testEntityUpdateException()
    {
        $this->expectException(EntityValidationException::class);

        $uuid = (string) Uuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id: new ValueObjectUuid($uuid),
            name: 'New genre',
            isActive: false,
            createdAt: new DateTime($date),
        );

        $genre->update(
            name: 'a',
        );
    }

    public function testAddCategoryToGenre()
    {
        $categoryId = (string) Uuid::uuid4();

        $genre = new Genre(
            name: 'New genre',
        );

        $this->assertIsArray($genre->categoriesId);
        $this->assertCount(0, $genre->categoriesId);

        $genre->addCategory(
            categoryId: $categoryId
        );

        $genre->addCategory(
            categoryId: $categoryId
        );

        $this->assertCount(2, $genre->categoriesId);
    }

    public function testRemoveCategoryFromGenre()
    {
        $categoryId = (string) Uuid::uuid4();
        $categoryId2 = (string) Uuid::uuid4();

        $genre = new Genre(
            name: 'New genre',
            categoriesId: [
                $categoryId,
                $categoryId2,
            ]
        );

        $this->assertCount(2, $genre->categoriesId);

        $genre->removeCategory(
            categoryId: $categoryId
        );

        $this->assertCount(1, $genre->categoriesId);
        $this->assertEquals($categoryId2, $genre->categoriesId[1]);
    }
}
