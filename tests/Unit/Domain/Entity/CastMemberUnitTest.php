<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Enum\CastMemberType;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\CastMember;
use Core\Domain\Exception\EntityValidationException;
use Ramsey\Uuid\Uuid;

class CastMemberUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) Uuid::uuid4();

        $castMember = new CastMember(
            id: new ValueObjectUuid($uuid),
            name: 'Cast Member Name',
            type: CastMemberType::ACTOR,
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );

        $this->assertEquals($uuid, $castMember->id());
        $this->assertEquals('Cast Member Name', $castMember->name);
        $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }

    public function testAttributesNewEntity()
    {
        $castMember = new CastMember(
            name: 'Cast Member Name',
            type: CastMemberType::DIRECTOR
        );

        $this->assertNotEmpty($castMember->id());
        $this->assertEquals('Cast Member Name', $castMember->name);
        $this->assertEquals(CastMemberType::DIRECTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }

    public function testValidation()
    {
        $this->expectException(EntityValidationException::class);

        new CastMember(
            name: 'Ca',
            type: CastMemberType::DIRECTOR,
        );
    }

    public function testExceptionUpdate()
    {
        $this->expectException(EntityValidationException::class);

        $castMember = new CastMember(
            name: 'Cast Member Name',
            type: CastMemberType::ACTOR,
        );

        $castMember->update(
            name: 'Ca',
        );
    }

    public function testUpdateEntity()
    {
        $castMember = new CastMember(
            name: 'Cast Member Name',
            type: CastMemberType::ACTOR,
        );

        $this->assertEquals('Cast Member Name', $castMember->name);

        $castMember->update(
            name: 'Updated Cast Member Name',
        );

        $this->assertEquals('Updated Cast Member Name', $castMember->name);
    }

}
