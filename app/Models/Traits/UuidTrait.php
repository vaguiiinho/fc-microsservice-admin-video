<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait  UuidTrait
{
     /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}