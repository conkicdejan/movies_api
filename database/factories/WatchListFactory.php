<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WatchList>
 */
class WatchListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'movie_id' => Movie::inRandomOrder()->first(),
            'user_id' => User::inRandomOrder()->first(),
            'watched' => $this->faker->boolean()
        ];
    }
}
