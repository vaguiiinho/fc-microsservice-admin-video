<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
   abstract protected function model(): Model;
   abstract protected function traits(): array;
   abstract protected function fillable(): array;
   abstract protected function casts(): array;

   abstract protected function incrementing(): bool;

   public function testIfUsedTraits()
   {
       $traitsNeeds = $this->traits();

       $traitsUsed = array_keys(class_uses($this->model()));

       $this->assertEquals($traitsNeeds, $traitsUsed);
   }

   public function testFillable()
   {
       $expected = $this->fillable();

       $fillable = $this->model()->getFillable();

       $this->assertEquals($expected, $fillable);
       
   }

   public function testIncrementingIsFalse()
   {
      
       $this->assertFalse($this->incrementing());
   }

   public function testCasts()
   {
       $expectedCasts = $this->casts();

       $casts = $this->model()->getCasts();

       $this->assertEquals($expectedCasts, $casts);
   }
}