<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'kcal' => $this->faker->randomFloat(2, 50, 1000),
            'fat' => $this->faker->randomFloat(2, 1, 100),
            'carb' => $this->faker->randomFloat(2, 1, 100),
            'protein' => $this->faker->randomFloat(2, 1, 100),
            'salt' => $this->faker->randomFloat(2, 0, 20),
            'sugar' => $this->faker->randomFloat(2, 0, 50),
        ];
    }
}
