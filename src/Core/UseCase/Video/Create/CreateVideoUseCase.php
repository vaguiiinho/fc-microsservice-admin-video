<?php

namespace Core\UseCase\Video\Create;

use Core\Domain\Entity\Video;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class CreateVideoUseCase
{

    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,
    ) {}

    public function exec(CreateInputVideoDTO $input): CreateOutputVideoDTO
    {

        $entity = $this->createEntity($input);

        try {
            $this->repository->insert($entity);

            if ($pathMedia = $this->storeMedia($entity->id(), $input->videoFile)) {
                $this->eventManager->dispatch(new VideoCreatedEvent($entity));
            }

            $this->transaction->commit();

            return new CreateOutputVideoDTO();
        } catch (\Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    private function createEntity(CreateInputVideoDTO $input): Video
    {

        $entity =  new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating,
        );

        foreach ($input->categories as $categoryId) {
            $entity->addCategory($categoryId);
        }

        foreach ($input->genres as $genreId) {
            $entity->addGenre($genreId);
        }

        foreach ($input->castMembers as $castMemberId) {
            $entity->addCastMember($castMemberId);
        }

        return $entity;
    }

    private function storeMedia(string $path, ?array $media = null): string
    {
        if ($media) {
            return  $this->storage->store(
                path: $path,
                file: $media
            );
        }

        return '';
    }
}
