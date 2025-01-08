<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as UuidValueObject;
use DateTime;
use Ramsey\Uuid\Uuid as Uuid;
use Tests\TestCase;

class GenreEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new GenreEloquentRepository(new Model);
    }

    public function test_implement_interface()
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }

    public function test_insert()
    {
        $entity = new EntityGenre(name: 'New Genre');
        $response = $this->repository->insert($entity);

        $this->assertEquals($entity->name, $response->name);
        $this->assertEquals($entity->id, $response->id);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'name' => $entity->name,
            'is_active' => $entity->isActive,
        ]);
    }

    public function test_insert_deactivate()
    {
        $entity = new EntityGenre(name: 'New Genre');
        $entity->deactivate();

        $this->repository->insert($entity);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'is_active' => false,
        ]);
    }

    public function test_insert_with_relationship()
    {
        $categories = Category::factory()->count(4)->create();

        $genre = new EntityGenre(name: 'New Genre');

        foreach ($categories as $category) {
            $genre->addCategory($category->id);
        }

        $response = $this->repository->insert($genre);

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id(),
        ]);

        $this->assertDatabaseCount('category_genre', 4);
    }

    public function test_not_found_by_id()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('not_found_id');
    }

    public function test_find_by_id()
    {
        $genre = Model::factory()->create();

        $response = $this->repository->findById($genre->id);

        $this->assertEquals($genre->id, $response->id);
        $this->assertEquals($genre->name, $response->name);
    }

    public function test_find_all()
    {
        $genres = Model::factory()->count(5)->create();

        $response = $this->repository->findAll();

        $this->assertEquals(count($genres), count($response));
    }

    public function test_find_all_with_filter()
    {
        Model::factory()->count(5)->create(['name' => 'test']);
        Model::factory()->count(5)->create();

        $response = $this->repository->findAll('test');

        $this->assertEquals(5, count($response));

        $response = $this->repository->findAll('fake');
        $this->assertEquals(0, count($response));

        $response = $this->repository->findAll();
        $this->assertEquals(10, count($response));
    }

    public function test_find_all_not_found()
    {
        $response = $this->repository->findAll();

        $this->assertCount(0, $response);
    }

    public function test_paginate()
    {
        Model::factory()->count(60)->create();

        $response = $this->repository->paginate();

        $this->assertCount(15, $response->items());
        $this->assertEquals(60, $response->total());
    }

    public function test_paginate_empty()
    {
        $response = $this->repository->paginate();

        $this->assertCount(0, $response->items());
        $this->assertEquals(0, $response->total());
    }

    public function test_update()
    {
        $genre = Model::factory()->create();

        $entity = new EntityGenre(
            id: new UuidValueObject($genre->id),
            name: $genre->name,
            isActive: (bool) $genre->is_active,
            createdAt: new DateTime($genre->created_at),
        );

        $updatedName = 'name updated';

        $entity->update(name: $updatedName);
        $response = $this->repository->update($entity);

        $this->assertEquals($updatedName, $response->name);
        $this->assertEquals($genre->id, $response->id);

        $this->assertDatabaseHas('genres', [
            'name' => $updatedName,
        ]);
    }

    public function test_update_not_found()
    {
        $this->expectException(NotFoundException::class);

        $uuid = (string) Uuid::uuid4();

        $entity = new EntityGenre(
            id: new UuidValueObject($uuid),
            name: 'genre',
        );

        $this->repository->update($entity);
    }

    public function test_delete_not_found()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->delete('fake_id');
    }

    public function test_delete()
    {
        $genre = Model::factory()->create();

        $response = $this->repository->delete($genre->id);

        $this->assertSoftDeleted('genres', ['id' => $genre->id]);

        $this->assertTrue($response);
    }
}
