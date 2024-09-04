<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as Model;
use App\Repositories\Presenter\PaginationPresenter;
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

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        // $query = $this->model->query();

        // if (!empty($filter)) {
        //     $query->where('name', 'LIKE', "%{$filter}%");
        // }

        // $query->orderBy('id', $order);

        // $genres = $query->get();

        $genres = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->get();

        return $genres->toArray();
    }

    public function paginate(
        string $filter = '',
        $order = 'DESC',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {

        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->paginate($totalPage);

        return new PaginationPresenter($result);
    }

    public function update(EntityGenre $entity): EntityGenre
    {
        if (!$genre = $this->model->find($entity->id())) {
            throw new NotFoundException("Genre {$entity->id()} not found");
        }

        $genre->update([
            'name' => $entity->name,
        ]);

        if (count($entity->categoriesId) > 0) {
            
            $genre->categories()->sync($entity->categoriesId);
        }

        $genre->refresh();

        return $this->toGenre($genre);
    }

    public function delete(string $id): bool 
    {
        if (!$genre = $this->model->find($id)) {
            throw new NotFoundException("Genre {$id} not found");
        }

        return $genre->delete();
    }

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
