<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class VideoUnitTest extends TestCase
{
    public function testAttributes()
    {
        $id = (string) Uuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            id: new ValueObjectUuid($id),
            published: true,
        );


        $this->assertEquals('new title', $entity->title);
        $this->assertEquals('description', $entity->description);
        $this->assertEquals(2029, $entity->yearLaunched);
        $this->assertEquals(12, $entity->duration);
        $this->assertTrue($entity->opened);
        $this->assertEquals(Rating::RATE12, $entity->rating);
        $this->assertEquals($id, $entity->id());
        $this->assertTrue($entity->published);
    }

    public function testId()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12
        );
        $this->assertNotEmpty($entity->id());
    }

    public function testAddCategory()
    {
        $categoryId = (string) Uuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12
        );
        $this->assertCount(0, $entity->categoriesId);

        $entity->addCategory(
            categoryId: $categoryId
        );
        
        $entity->addCategory(
            categoryId: $categoryId
        );

        $this->assertCount(2, $entity->categoriesId);
    }

    public function testRemoveCategory()
    {
        $categoryId = (string) Uuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12
        );

        $entity->addCategory(
            categoryId: $categoryId
        );
        
        $entity->addCategory(
            categoryId: 'teste'
        );

        $this->assertCount(2, $entity->categoriesId);

       
        $entity->removeCategory(
            categoryId: 'fake_id'
        );

        $this->assertCount(2, $entity->categoriesId);

        $entity->removeCategory(
            categoryId: $categoryId
        );

        $this->assertCount(1, $entity->categoriesId);
    }

    public function testAddGenre()
    {
        $genreId = (string) Uuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12
        );

        $this->assertCount(0, $entity->genresId);

        $entity->addGenre(
            genreId: $genreId
        );
        
        $entity->addGenre(
            genreId: 'teste'
        );

        $this->assertCount(2, $entity->genresId);
    }

    public function testRemoveGenre()
    {
        $genreId = (string) Uuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12
        );

        $entity->addGenre(
            genreId: $genreId
        );
        
        $entity->addGenre(
            genreId: 'teste'
        );

        $this->assertCount(2, $entity->genresId);

       
        $entity->removeGenre(
            genreId: 'fake_id'
        );

        $this->assertCount(2, $entity->genresId);

        $entity->removeGenre(
            genreId: $genreId
        );

        $this->assertCount(1, $entity->genresId);
    }
}
