<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'title' => $this->faker->city(),
            'description' => $this->faker->text(),
            'cover_image' => "https://spaceplace.nasa.gov/blue-sky/en/bluesky.en.png",
            'category_id' => Category::inRandomOrder()->first(),
        ];
    }
}
