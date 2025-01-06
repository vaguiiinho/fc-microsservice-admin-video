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
    public function updateMediaVideo(Entity $entity, Model $model): void
    {
        if ($video = $entity->videoFile()) {
            $action = $model->media()->first() ? 'update' : 'create';
            $model->media()->{$action}([
                'file_path' => $video->filePath,
                'media_status' => (string) $video->mediaStatus->value,
                'encoded_path' => $video->encodedPath,
                'type' => (string) MediaTypes::VIDEO->value,
            ]);
        }
    }

    public function updateMediaTrailer(Entity $entity, Model $model): void
    {
        if ($trailer = $entity->trailerFile()) {
            $action = $model->trailer()->first() ? 'update' : 'create';
            $model->trailer()->{$action}([
                'file_path' => $trailer->filePath,
                'media_status' => (string) $trailer->mediaStatus->value,
                'encoded_path' => $trailer->encodedPath,
                'type' => (string) MediaTypes::TRAILER->value,
            ]);
        }
    }

    public function updateImageThumb(Entity $entity, Model $model): void
    {
        if ($thumb = $entity->thumbFile()) {
            $action = $model->thumb()->first() ? 'update' : 'create';
            $model->thumb()->{$action}([
                'path' => $thumb->path(),
                'type' => (string) ImageTypes::THUMB->value,
            ]);
        }
    }

    public function updateImageThumbHalf(Entity $entity, Model $model): void
    {
        if ($thumbHalf = $entity->thumbHalf()) {
            $action = $model->thumbHalf()->first() ? 'update' : 'create';
            $model->thumbHalf()->{$action}([
                'path' => $thumbHalf->path(),
                'type' => (string) ImageTypes::THUMB_HALF->value,
            ]);
        }
    }

    public function updateImageBanner(Entity $entity, Model $model): void
    {
        if ($banner = $entity->bannerFile()) {
            $action = $model->banner()->first() ? 'update' : 'create';
            $model->banner()->{$action}([
                'path' => $banner->path(),
                'type' => (string) ImageTypes::BANNER->value,
            ]);
        }
    }
}
