<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'content' => $this->faker->text(random_int(5,500)),
            'created_at' => $this->faker->dateTimeThisDecade('now', 'Europe/Belgrade')
        ];
    }
}
