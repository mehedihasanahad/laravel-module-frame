<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'form_id' => 1,
            'parent_id' => 1, // refers to self primary key
            'title' => fake()->jobTitle(),
            'is_loop' => 0,
            'template_type' => 1, // 1 => 2 Column Grid ,2 => 4 Column Grid , 3 => Tabular Form
            'order' => 1,
            'step_no' => 0,
            'status' => 1,
        ];
    }
}
