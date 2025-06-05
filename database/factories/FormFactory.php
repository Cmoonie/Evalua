<?php

namespace Database\Factories;

use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\form>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Form::class;
    public function definition(): array
    {
        return [
            //
        ];
    }
}
