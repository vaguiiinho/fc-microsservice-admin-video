<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Eloquent\CastMemberRepository;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Tests\TestCase;

class CastMemberRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CastMemberRepository(new Model());
    }
    public function testCheckImplementsCastMemberRepository()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }

    public function testInsertCastMember()
    {
        $entity = new Entity(
            name: 'new Cast Member',
            type: CastMemberType::ACTOR,
        );

        $response = $this->repository->insert($entity);

        $this->assertDatabaseHas('cast_members', [
            'id' => $entity->id(),
        ]);
        $this->assertEquals('new Cast Member', $response->name);
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById('fake-id');
    }

    public function testFindById()
    {
        $castMember = Model::factory()->create();

        $response = $this->repository->findById($castMember->id);

        $this->assertEquals($castMember->id, $response->id());
        $this->assertEquals($castMember->name, $response->name);
    }
}