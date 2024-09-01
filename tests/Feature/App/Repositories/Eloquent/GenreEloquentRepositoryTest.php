<?php

namespace Tests\Feature\App\Repositories\Eloquent;

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
}
