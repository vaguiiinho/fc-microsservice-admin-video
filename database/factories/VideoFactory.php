<?php

namespace Database\Factories;

use Carbon\Carbon;
use Core\Domain\Enum\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(10),
            'year_launched' => Carbon::make(now()->addYears(5))->format('Y'),
            'opened' => $this->faker->boolean(),
            'rating' => Rating::L,
            'duration' => 1,
            'created_at' => now()
        ];
    }
}
