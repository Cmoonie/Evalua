<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition()
    {
        return [
            'user_id'     => User::factory(), // creates a user automatically if not provided
            'title'       => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'subject'     => $this->faker->word,
            'oe_code'     => $this->faker->bothify('??###'),
        ];
    }
}
