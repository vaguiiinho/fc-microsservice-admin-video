<?php

namespace App\Repositories\Eloquent\Traits;

use App\Enums\{
    ImageTypes,
    MediaTypes
};
use App\Models\Video as Model;
use Core\Domain\Entity\Video as Entity;

trait VideoTrait
{
    public function updateMediaVideo(): void
    {

    }

    public function updateMediaTrailer(Entity $entity, Model $model): void
    {
        if ($trailer = $entity->trailerFile()) {
            $action = $model->trailer()->first() ? 'update' : 'create';
            $model->trailer()->{$action}([
                'file_path' => $trailer->filePath,
                'media_status' => $trailer->mediaStatus->value,
                'encoded_path' => $trailer->encodedPath,
                'type' => MediaTypes::TRAILER->value,
            ]);
        }

    }

    public function updateImageThumb(): void
    {

    }

    public function updateImageThumbHalf(): void
    {

    }

    public function updateImageBanner(Entity $entity, Model $model): void
    {

        if ($banner = $entity->bannerFile()) {
            $action = $model->banner()->first() ? 'update' : 'create';
            $model->banner()->{$action}([
                'path' => $banner->path(),
                'type' => ImageTypes::BANNER->value,
            ]);
        }
    }
}