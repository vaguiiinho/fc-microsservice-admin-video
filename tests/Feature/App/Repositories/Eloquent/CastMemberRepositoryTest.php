<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Eloquent\CastMemberRepository;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;

class CastMemberRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CastMemberRepository(new Model);
    }

    public function test_check_implements_cast_member_repository()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }

    public function test_insert_cast_member()
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

    public function test_find_by_id_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById('fake-id');
    }

    public function test_find_by_id()
    {
        $castMember = Model::factory()->create();

        $response = $this->repository->findById($castMember->id);

        $this->assertEquals($castMember->id, $response->id());
        $this->assertEquals($castMember->name, $response->name);
    }

    public function test_find_all_empty()
    {
        $response = $this->repository->findAll();

        $this->assertCount(0, $response);
    }

    public function test_find_all()
    {
        $castMembers = Model::factory()->count(50)->create();

        $response = $this->repository->findAll();

        $this->assertCount(count($castMembers), $response);
    }

    public function test_pagination()
    {
        Model::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertCount(15, $response->items());
        $this->assertEquals(20, $response->total());
    }

    public function test_pagination_page2()
    {
        Model::factory()->count(80)->create();

        $response = $this->repository->paginate(
            totalPage: 10
        );

        $this->assertCount(10, $response->items());
        $this->assertEquals(80, $response->total());
    }

    public function test_update_not_found()
    {
        $this->expectException(NotFoundException::class);

        $entity = new Entity(
            name: 'new Cast Member',
            type: CastMemberType::ACTOR,
        );

        $this->repository->update($entity);
    }

    public function test_update()
    {
        $castMember = Model::factory()->create();

        $entity = new Entity(
            id: new Uuid($castMember->id),
            name: 'updated Cast Member',
            type: CastMemberType::ACTOR,
        );

        $response = $this->repository->update($entity);

        $this->assertEquals('updated Cast Member', $response->name);
    }

    public function test_delete_not_found()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->delete('fake-id');
    }

    public function test_delete()
    {
        $castMember = Model::factory()->create();

        $this->repository->delete($castMember->id);

        $this->assertSoftDeleted('cast_members', ['id' => $castMember->id]);
    }
}
