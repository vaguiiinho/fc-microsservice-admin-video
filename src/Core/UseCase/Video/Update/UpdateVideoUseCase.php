<?php

namespace Core\UseCase\Video\Update;

use Core\UseCase\Video\BaseVideoUseCase;
use Core\UseCase\Video\Update\DTO\{
    UpdateInputVideoDTO,
    UpdateOutputVideoDTO
};

class UpdateVideoUseCase extends BaseVideoUseCase
{
    public function exec(UpdateInputVideoDTO $input): UpdateOutputVideoDTO
    {
        // Implement logic to update video
    }
}