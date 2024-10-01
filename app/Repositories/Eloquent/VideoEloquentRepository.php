<?php

namespace App\Repositories\Eloquent;

use App\Models\Video as Model;
use Core\Domain\Entity\{
    Entity,
    Video,
};
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $entity): Entity
    {
        $entityDb = $this->model->create([
            'id' => $entity->id(),
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
            'rating' => $entity->rating->value,
        ]);

        $this->syncRelationships($entityDb, $entity);

        return $this->convertObjectToEntity($entityDb);
    }

    public function findById(string $id): Entity {}

    public function findAll(string $filter = '', $order = 'DESC'): array {}

    public function paginate(
        string $filter = '',
        $order = 'DESC',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {}

    public function update(Entity $entity): Entity {}

    public function delete(string $id): bool {}

    public function updateMedia(Entity $entity): Entity {}

    protected function syncRelationships(Model $model, Entity $entity)
    {
        $model->categories()->sync($entity->categoriesId);
        $model->genres()->sync($entity->genresId);
        $model->castMembers()->sync($entity->castMembersId);
    }

    protected function convertObjectToEntity(object $object): Video
    {
        return new Video(
            id: new Uuid($object->id),
            title: $object->title,
            description: $object->description,
            yearLaunched: (int) $object->year_launched,
            duration: (bool) $object->duration,
            opened: $object->opened,
            rating: Rating::from($object->rating),
        );
    }
}
