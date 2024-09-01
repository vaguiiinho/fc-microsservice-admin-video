<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as Model;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(EntityGenre $genre): EntityGenre
    {
        $register =  $this->model->create([
            'id' => $genre->id(),
            'name' => $genre->name,
            'is_active' => $genre->isActive,
            'created_at' => $genre->createdAt(),
        ]);

        if (count($genre->categoriesId) > 0) {
            $register->categories()->sync($genre->categoriesId);
        }

        return $this->toGenre($register);
    }

    public function findById(string $id): EntityGenre
    {
        if (!$genre = $this->model->find($id)) {
            throw new NotFoundException("Genre {$id} not found");
        }

        return $this->toGenre($genre);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array {}

    public function paginate(
        string $filter = '',
        $order = 'DESC',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {}

    public function update(EntityGenre $genre): EntityGenre {}

    public function delete(string $id): bool {}

    private function toGenre(Model $object): EntityGenre
    {
        $entity =  new EntityGenre(
            id: new Uuid($object->id),
            name: $object->name,
            createdAt: new DateTime($object->created_at),
        );

        ((bool) $object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}
