<?php

namespace Database\Seeders;

use App\Core\FormBuilder\Models\Component;
use App\Core\FormBuilder\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Form::factory()
            ->count(1)
            ->has(Component::factory()->count(5), 'components')
            ->create();


    }
}
