<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use Core\Domain\Exception\NotFoundException;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Tests\TestCase;
use Throwable;

class GenreEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new GenreEloquentRepository(new Model());
    }

    public function testImplementInterface()
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
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

    public function testInsertDeactivate()
    {
        $entity = new EntityGenre(name: 'New Genre');
        $entity->deactivate();

        $this->repository->insert($entity);


        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'is_active' => false,
        ]);
    }

    public function testInsertWithRelationship()
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

    public function testNotFoundById()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('not_found_id');
    }

    public function testFindById()
    {
        $genre = Model::factory()->create();

        $response = $this->repository->findById($genre->id);

        $this->assertEquals($genre->id, $response->id);
        $this->assertEquals($genre->name, $response->name);
    }

    public function testFindAll()
    {
        $genres = Model::factory()->count(5)->create();

        $response = $this->repository->findAll();

        $this->assertEquals(count($genres), count($response));
    }

    public function testFindAllWithFilter()
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

    public function testFindAllNotFound()
    {
        $response = $this->repository->findAll();

        $this->assertCount(0, $response);
    }

    public function testPaginate()
    {
        Model::factory()->count(60)->create();

        $response = $this->repository->paginate();

        $this->assertCount(15, $response->items());
        $this->assertEquals(60, $response->total());
    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();

        $this->assertCount(0, $response->items());
        $this->assertEquals(0, $response->total());
    }
}
