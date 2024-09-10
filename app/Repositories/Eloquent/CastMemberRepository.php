<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Presenter\PaginationPresenter;
use Core\Domain\Entity\CastMember as Entity;
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

    public function insert(Entity $entity): Entity {
        $response = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'type' => $entity->type->value,
            'created_at' => $entity->createdAt(),
        ]);
        return $this->toEntity($response);
    }

    public function findById(string $id): Entity {

    }

    public function findAll(string $filter = '', $order = 'DESC'): array {}

    public function paginate(
        string $filter = '',
        $order = 'DESC',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {}

    public function update(Entity $entity): Entity {}

    public function delete(string $entityId): bool {}

    private function toEntity(object $entity): Entity
    {
        return new Entity(
            id: new Uuid($entity->id),
            name: $entity->name,
            type: CastMemberType::from($entity->type),
            createdAt: $entity->createAt,
        );
    }
}
