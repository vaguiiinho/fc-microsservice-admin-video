<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as ModelsCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Tests\TestCase;
use Throwable;

class CategoryEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CategoryEloquentRepository(new ModelsCategory);
        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
    }

    public function test_insert()
    {

        $entity = new EntityCategory(name: 'test');

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name,
        ]);
    }

    public function test_find_by_id()
    {
        $category = ModelsCategory::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id());
    }

    public function test_find_by_id_not_found()
    {
        try {
            $this->repository->findById('fake');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function test_find_all()
    {
        $categories = ModelsCategory::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertEquals(count($categories), count($response));
    }

    public function test_paginate()
    {
        ModelsCategory::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
    }

    public function test_paginate_without()
    {
        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
    }

    public function test_update_id_not_found()
    {
        try {
            $category = new EntityCategory(name: 'test');
            $this->repository->update($category);
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function test_update()
    {
        $category = ModelsCategory::factory()->create();

        $entity = new EntityCategory(id: $category->id, name: 'updated');

        $response = $this->repository->update($entity);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($entity->id, $response->id());
        $this->assertEquals('updated', $response->name);
    }

    public function test_delete_id_not_found()
    {
        try {
            $this->repository->delete('fake');
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th, 'Category Not Found');
        }
    }

    public function test_delete()
    {
        $category = ModelsCategory::factory()->create();

        $response = $this->repository->delete($category->id);

        $this->assertTrue($response);
    }
}
