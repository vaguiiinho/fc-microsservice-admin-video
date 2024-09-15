<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\{
    Image,
    Media
};
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use DateTime;
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
            createdAt: new DateTime(date('Y-m-d H:i:s'))
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

    public function testIdAndCreatedAt()
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
        $this->assertNotEmpty($entity->createdAt());
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

    public function testAddCastMember()
    {
        $castMemberId = (string) Uuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12
        );

        $this->assertCount(0, $entity->castMembersId);

        $entity->addCastMember(
            castMemberId: $castMemberId
        );

        $entity->addCastMember(
            castMemberId: 'teste'
        );

        $this->assertCount(2, $entity->castMembersId);
    }

    public function testRemoveCastMember()
    {
        $castMemberId = (string) Uuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12
        );

        $entity->addCastMember(
            castMemberId: $castMemberId
        );

        $entity->addCastMember(
            castMemberId: 'teste'
        );

        $this->assertCount(2, $entity->castMembersId);


        $entity->removeCastMember(
            castMemberId: 'fake_id'
        );

        $this->assertCount(2, $entity->castMembersId);

        $entity->removeCastMember(
            castMemberId: $castMemberId
        );

        $this->assertCount(1, $entity->castMembersId);
    }

    public function testValueObjectImage()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            thumbFile: new Image(
                path: 'fsdfsd/image-filmex.png'
            )
        );
        $this->assertNotEmpty($entity->thumbFile());
        $this->assertInstanceOf(Image::class, $entity->thumbFile());
        $this->assertEquals('fsdfsd/image-filmex.png', $entity->thumbFile()->path());
    }

    public function testValueObjectImageThumbHalf()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            thumbHalf: new Image('bbbbbb/image-half.png')
        );
        $this->assertNotEmpty($entity->thumbHalf());
        $this->assertInstanceOf(Image::class, $entity->thumbHalf());
        $this->assertEquals('bbbbbb/image-half.png', $entity->thumbHalf()->path());
    }

    public function testValueObjectMedia()
    {
        $trailerFile = new Media(
            filePath: 'path/video.mp4',
            mediaStatus:  MediaStatus::PENDING,
            encodePath: 'path/encoded.extension'
        );

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            trailerFile: $trailerFile
        );
       
        $this->assertNotNull($entity->trailerFile());
        $this->assertInstanceOf(Media::class, $entity->trailerFile());
        $this->assertEquals('path/video.mp4', $entity->trailerFile()->filePath);

    }
}
