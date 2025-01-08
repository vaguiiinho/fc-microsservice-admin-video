<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;

    abstract protected function traits(): array;

    abstract protected function fillable(): array;

    abstract protected function casts(): array;

    abstract protected function incrementing(): bool;

    public function test_if_used_traits()
    {
        $traitsNeeds = $this->traits();

        $traitsUsed = array_keys(class_uses($this->model()));

        $this->assertEquals($traitsNeeds, $traitsUsed);
    }

    public function test_fillable()
    {
        $expected = $this->fillable();

        $fillable = $this->model()->getFillable();

        $this->assertEquals($expected, $fillable);

    }

    public function test_incrementing_is_false()
    {

        $this->assertFalse($this->incrementing());
    }

    public function test_casts()
    {
        $expectedCasts = $this->casts();

        $casts = $this->model()->getCasts();

        $this->assertEquals($expectedCasts, $casts);
    }
}
