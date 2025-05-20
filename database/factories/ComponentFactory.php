<?php

namespace Database\Factories;

use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\component>
 */
class ComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Component::class;

    public function definition(): array
    {
        return [
                'name' => fake()->words(2, true),
                'description' => fake()->paragraph(),
        ];
    }
}
