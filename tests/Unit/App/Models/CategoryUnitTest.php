<?php

namespace Tests\Unit\App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends TestCase
{
    protected function model(): Model
    {
        return new Category();
    }

    public function testIfUsedTraits()
    {
        $traitsNeeds = [
            HasFactory::class,
            SoftDeletes::class,
        ];

        $traitsUsed = array_keys(class_uses($this->model()));

        $this->assertEquals($traitsNeeds, $traitsUsed);
    }

    public function testFillable()
    {
        $expected = [
            'id',
            'name',
            'description',
            'is_active',
        ];

        $fillable = $this->model()->getFillable();

        $this->assertEquals($expected, $fillable);
        
    }

    public function testIncrementingIsFalse()
    {
        $model = $this->model();
        $this->assertFalse($model->incrementing);
    }

    public function testCasts()
    {
        $expectedCasts = [
            'id' =>'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];

        $casts = $this->model()->getCasts();

        $this->assertEquals($expectedCasts, $casts);
    }
}
