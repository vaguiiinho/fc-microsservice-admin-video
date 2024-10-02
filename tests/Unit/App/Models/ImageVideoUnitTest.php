<?php

namespace Tests\Unit\App\Models;

use App\Models\ImageVideo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UuidTrait;

class ImageVideoUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new ImageVideo();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            UuidTrait::class,
        ];
    }

    protected function fillable(): array
    {
        return [
            'path',
            'type',
        ];
    }

    protected function incrementing(): bool
    {
        return false;
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }
}
