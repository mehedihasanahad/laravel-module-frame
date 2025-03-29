<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'process_type_id' => 1,
            'form_type' => 1,
            'template_type' => 1,
            'steps' => 0,
            'title' => fake()->name(),
            'form_data_json' => fake()->name(),
            'method' => fake()->name(),
            'action' => fake()->name(),
            'enctype' => fake()->name(),
            'status' =>1,
        ];
    }
}
