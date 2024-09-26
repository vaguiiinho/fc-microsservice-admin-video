<?php

namespace Core\UseCase\Video\Update;

use Core\Domain\Builder\Video\{
    UpdateVideoBuilder,
    Builder
};
use Core\UseCase\Video\BaseVideoUseCase;
use Core\UseCase\Video\Update\DTO\{
    UpdateInputVideoDTO,
    UpdateOutputVideoDTO
};

class UpdateVideoUseCase extends BaseVideoUseCase
{
    protected function getBuilder(): Builder
    {
        return new UpdateVideoBuilder;
    }
    public function exec(UpdateInputVideoDTO $input): UpdateOutputVideoDTO
    {
        // Implement logic to update video
    }
}