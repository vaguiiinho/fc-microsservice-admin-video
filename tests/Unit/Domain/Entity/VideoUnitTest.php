<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Video;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class VideoUnitTest extends TestCase
{
    public function testAttributes()
    {
        $id = (string) Uuid::uuid4();

        $entity = new Video(
            id: new ValueObjectUuid($id),
            title: 'new title',
            description: 'description'

        );


        $this->assertTrue(true);
    }
}
