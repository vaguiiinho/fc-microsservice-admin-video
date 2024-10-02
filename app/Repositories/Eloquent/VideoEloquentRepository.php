<?php

namespace App\Repositories\Eloquent;

use App\Enums\{
    ImageTypes,
    MediaTypes
};
use App\Models\Video as Model;
use App\Repositories\Eloquent\Traits\VideoTrait;
use App\Repositories\Presenter\PaginationPresenter;
use Core\Domain\Entity\{
    Entity,
    Video,
};
use Core\Domain\Enum\{
    Rating,
    MediaStatus
};
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    VideoRepositoryInterface,
    PaginationInterface
};
use Core\Domain\ValueObject\{
    Image,
    Media,
    Uuid
};

class VideoEloquentRepository implements VideoRepositoryInterface
{
    use VideoTrait;

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

    public function findById(string $id): Entity
    {
        if (!$entityDb = $this->model->find($id)) {
            throw new NotFoundException('Video not found');
        }

        return $this->convertObjectToEntity($entityDb);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $videos = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('title', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('title', $order)
            ->get();

        return $videos->toArray();
    }

    public function paginate(
        string $filter = '',
        $order = 'DESC',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {
        $query = $this->model->query();

        if (!empty($filter)) {
            $query->where('title', 'LIKE', "%{$filter}%");
        }

        $query->orderBy('title', $order);

        $pagination = $query->paginate(
            perPage: $totalPage,
            page: $page
        );

        return new PaginationPresenter($pagination);
    }

    public function update(Entity $entity): Entity
    {
        if (!$entityDb = $this->model->find($entity->id())) {
            throw new NotFoundException('Video not found');
        }

        $entityDb->update([
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
            'rating' => $entity->rating->value,
        ]);

        $entityDb->refresh();

        $this->syncRelationships($entityDb, $entity);

        return $this->convertObjectToEntity($entityDb);
    }

    public function delete(string $id): bool
    {
        if (!$entityDb = $this->model->find($id)) {
            throw new NotFoundException('Video not found');
        }

        return $entityDb->delete();
    }

    public function updateMedia(Entity $entity): Entity
    {
        if (!$entityDb = $this->model->find($entity->id())) {
            throw new NotFoundException('Video not found');
        }

        $this->updateMediaTrailer($entity, $entityDb);

        $this->updateImageBanner($entity, $entityDb);

        return $this->convertObjectToEntity($entityDb);
    }

    protected function syncRelationships(Model $model, Entity $entity)
    {
        $model->categories()->sync($entity->categoriesId);
        $model->genres()->sync($entity->genresId);
        $model->castMembers()->sync($entity->castMembersId);
    }

    protected function convertObjectToEntity(object $model): Video
    {
        $entity = new Video(
            id: new Uuid($model->id),
            title: $model->title,
            description: $model->description,
            yearLaunched: (int) $model->year_launched,
            duration: (bool) $model->duration,
            opened: $model->opened,
            rating: Rating::from($model->rating),
        );

        foreach ($model->categories as $category) {
            $entity->addCategory($category->id);
        }

        foreach ($model->genres as $genre) {
            $entity->addGenre($genre->id);
        }

        foreach ($model->castMembers as $castMember) {
            $entity->addCastMember($castMember->id);
        }

        if ($trailer = $model->trailer) {
            $entity->setTrailerFile(new Media(
                filePath: $trailer->file_path,
                mediaStatus: MediaStatus::from($trailer->media_status),
                encodedPath: $trailer->encoded_path,
            ));
        }

        if ($banner = $model->banner) {
            $entity->setBannerFile(new Image($banner->path));
        }

        return $entity;
    }
}
