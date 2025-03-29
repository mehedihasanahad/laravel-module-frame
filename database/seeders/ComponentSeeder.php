<?php

namespace Database\Seeders;

use App\Core\FormBuilder\Models\Component;
use Illuminate\Database\Seeder;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Component::factory()
            ->count(4)
            ->create();
    }
}
