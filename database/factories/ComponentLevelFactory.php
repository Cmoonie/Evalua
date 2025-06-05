<?php

namespace Database\Factories;

use App\Models\ComponentLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComponentLevel>
 */
class ComponentLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ComponentLevel::class;
    public function definition(): array
    {
        return [
            'description' => fake()->paragraph(),
        ];
    }
}
