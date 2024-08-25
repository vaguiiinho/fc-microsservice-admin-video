<?php

namespace Tests\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Genre;
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


}
