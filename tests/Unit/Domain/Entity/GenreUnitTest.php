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
        $genre = new Genre(
            id: new ValueObjectUuid($uuid),
            name: 'New genre',
            isActive: true,
            createAt: new DateTime(date('Y-m-d H:i:s')),
        );

    }
}
