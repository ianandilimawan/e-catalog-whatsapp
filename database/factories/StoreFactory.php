<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'wa_number' => fake()->sentence(),
            'theme_color' => fake()->hexColor(),
            'welcome_message' => fake()->paragraph(),
            'logo' => fake()->imageUrl(),
            'banner' => fake()->imageUrl(),
            'button_rounded' => fake()->boolean(),
            'dark_mode' => fake()->boolean(),
        ];
    }
}
