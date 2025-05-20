<?php

namespace Database\Factories;

use App\Models\Competency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\competency>
 */
class CompetencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Competency::class;

    public function definition(): array
    {
        return [
            'domain_description' => fake()->paragraph(),
            'rating_scale' => fake()->paragraph(),
            'complexity' => fake()->paragraph(),
        ];
    }
}
