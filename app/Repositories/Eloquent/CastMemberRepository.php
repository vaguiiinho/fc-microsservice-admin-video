<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Presenter\PaginationPresenter;
use Core\Domain\Entity\CastMember;
use Core\Domain\Entity\Entity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;

class CastMemberRepository implements CastMemberRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $entity): Entity
    {
        $modelDb = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'type' => $entity->type->value,
            'created_at' => $entity->createdAt(),
        ]);

        return $this->toEntity($modelDb);
    }

    public function findById(string $id): Entity
    {
        if (! $modelDb = $this->model->find($id)) {
            throw new NotFoundException("Cast member {$id} not found");
        }

        return $this->toEntity($modelDb);
    }

    public function getIdsListIds(array $castMemberIds = []): array
    {
        return $this->model
            ->whereIn('id', $castMemberIds)
            ->pluck('id')
            ->toArray();
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        // $modelDb = $this->model
        //     ->where(function ($query) use ($filter) {
        //         if ($filter) {
        //             $query->where('name', 'LIKE', "%{$filter}%");
        //         }
        //     })
        //     ->orderBy('name', $order)
        //     ->get();

        // return $modelDb->toArray();

        $query = $this->model->query();

        if (! empty($filter)) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }

        $query->orderBy('name', $order);
        $modelDb = $query->get();

        return $modelDb->toArray();
    }

    public function paginate(
        string $filter = '',
        $order = 'DESC',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {
        $query = $this->model->query();
        if ($filter == '1' || $filter == '2') {
            $query->where('type', 'LIKE', "%{$filter}%");
        } elseif (! empty($filter)) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }

        $query->orderBy('name', $order);

        $paginator = $query->paginate(perPage: $totalPage, page: $page);

        return new PaginationPresenter($paginator);
    }

    public function update(Entity $entity): Entity
    {
        if (! $dataDb = $this->model->find($entity->id())) {
            throw new NotFoundException("Cast member {$entity->id()} not found");
        }

        $dataDb->update([
            'name' => $entity->name,
            'type' => $entity->type->value,
        ]);

        $dataDb->refresh();

        return $this->toEntity($dataDb);
    }

    public function delete(string $entityId): bool
    {
        if (! $dataDb = $this->model->find($entityId)) {
            throw new NotFoundException("Cast member {$entityId} not found");
        }

        return $dataDb->delete();
    }

    private function toEntity(object $entity): CastMember
    {
        return new CastMember(
            id: new Uuid($entity->id),
            name: $entity->name,
            type: CastMemberType::from($entity->type),
            createdAt: $entity->created_at,
        );
    }
}
