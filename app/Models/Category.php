<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'description',
        'is_active',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
